<?php

namespace Modules\PhalconVn;

use Phalcon\Mvc\User\Component;
use Modules\Models\Subdomain;
use Modules\Models\SubdomainRating;
use Modules\Models\MenuItem;
use Modules\Models\Category;
use Modules\Models\NewsMenu;
use Modules\Models\Product;
use Modules\Models\News;
use Modules\Models\TmpProductCategory;
use Modules\Models\TmpNewsNewsMenu;
use Modules\Models\TmpProductProductElementDetail;
use Phalcon\Paginator\Adapter\NativeArray;
use Modules\PhalconVn\DirectAdmin;

class ElasticService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->directAdmin = new DirectAdmin();
        $this->directAdmin->connect($this->config->directAdmin->ip, $this->config->directAdmin->port);
        $this->directAdmin->set_login($this->config->directAdmin->username, $this->config->directAdmin->password);
        $this->directAdmin->set_method('get');
    }

    /**
     * index elastic subdomain table
     * @param  Modules\Models\Subdomain $subdomains
     * @return array
     */
    public function _indexSubdomain($subdomains)
    {
        foreach ($subdomains as $key => $subdomain) {
            $params['body'][] = [
                'index' => [
                    '_index' => getenv('ELASTICSEARCH_INDEX_SELECT'),
                    '_type' => 'subdomain',
                    '_id' => $subdomain->id
                ]
            ];

            $sumRate = SubdomainRating::sum(
                [
                    "column"     => "rate",
                    "conditions" => "subdomain_id = $subdomain->id",
                ]
            );

            // map domain
            $mapDomain = ['name' => ''];
            if (count($subdomain->domain) > 0) {
                $domainName = '';
                foreach ($subdomain->domain as $keyDomain => $domain) {
                    $domainName .= $domain->name;
                    if ($keyDomain < count($subdomain->domain) - 1) {
                        $domainName .= ', ';
                    }
                }

                $mapDomain['name'] = $domainName;
            }

            // map menu item
            $mapMenuItem = [
                'name' => '',
                'slug' => ''
            ];
            if (count($subdomain->menuItem) > 0) {
                $menuItemName = '';
                $menuItemUrl = '';
                foreach ($subdomain->menuItem as $keyMenu => $menu) {
                    if ($menu->active == 'Y' && $menu->deleted == 'N') {
                        $menuItemName .= $menu->name;
                        $menuItemUrl .= $this->general->create_slug_three($menu->name);
                        if ($keyMenu < count($subdomain->menuItem) - 1) {
                            $menuItemName .= ', ';
                            $menuItemUrl .= ', ';
                        }
                    }
                }

                $mapMenuItem = [
                    'name' => $menuItemName,
                    'slug' => $menuItemUrl,
                ];
            }

            // map menu item
            $mapCategory = [
                'name' => '',
                'slug' => ''
            ];
            if (count($subdomain->category) > 0) {
                $categoryName = '';
                $categorySlug = '';
                foreach ($subdomain->category as $keyCate => $cate) {
                    if ($cate->active == 'Y' && $cate->deleted == 'N') {
                        $categoryName .= $cate->name;
                        $categorySlug .= $this->general->create_slug_three($cate->name);
                        if ($keyCate < count($subdomain->category) - 1) {
                            $categoryName .= ', ';
                            $categorySlug .= ', ';
                        }
                    }
                }

                $mapCategory = [
                    'name' => $categoryName,
                    'slug' => $categorySlug,
                ];
            }

            // map menu item
            $mapNewsMenu = [
                'name' => '',
                'slug' => ''
            ];
            if (count($subdomain->newsMenu) > 0) {
                $newsMenuName = '';
                $newsMenuSlug = '';
                foreach ($subdomain->newsMenu as $keyCate => $cate) {
                    if ($cate->active == 'Y' && $cate->deleted == 'N') {
                        $newsMenuName .= $cate->name;
                        $newsMenuSlug .= $this->general->create_slug_three($cate->name);
                        if ($keyCate < count($subdomain->newsMenu) - 1) {
                            $newsMenuName .= ', ';
                            $newsMenuSlug .= ', ';
                        }
                    }
                }

                $mapNewsMenu = [
                    'name' => $newsMenuName,
                    'slug' => $newsMenuSlug,
                ];
            }

            // map product
            $mapProduct = [
                'name' => '',
                'slug' => ''
            ];

            $mapNews = [
                'name' => '',
                'slug' => ''
            ];

            $source = $subdomain->toArray();
            unset($source['id']);
            $source['sum_rate'] = $sumRate > 0 ? $sumRate : 0;
            $source['domain'] = $mapDomain;
            $source['menuItem'] = $mapMenuItem;
            $source['category'] = $mapCategory;
            $source['newsMenu'] = $mapNewsMenu;
            $source['product'] = $mapProduct;
            $source['news'] = $mapNews;

            $params['body'][] = $source;
        }

        $responses = $this->elastic->bulk($params);

        return $responses;
    }

    /**
     * index elastic product table
     * @param  Modules\Models\Subdomain $subdomains
     * @return array
     */
    public function _indexTable($items, $type)
    {
        $responses = null;
        if (count($items) > 0) {
            foreach ($items as $key => $item) {
                $id = isset($item->id) ? $item->id : $key + 1;
                switch ($type) {
                    case 'tmp_product_category':
                        $id = $item->product_id . '_' .  $item->category_id;
                        break;

                    case 'tmp_news_news_menu':
                        $id = $item->news_id . '_' .  $item->news_menu_id;
                        break;

                    default:
                        $id = $item->id;
                        break;
                }

                $params['body'][] = [
                    'index' => [
                        '_index' => getenv('ELASTICSEARCH_INDEX'),
                        '_type' => $type,
                        '_id' => $id
                    ]
                ];

                $data = $item->toArray();

                $params['body'][] = $data;
            }

            $responses = $this->elastic->bulk($params);
        }

        return $responses;
    }

    /**
     * run action add subdomain to elasticsearch
     * @param int $id subdomain_id
     */
    public function addQueuueIndexSubdomainId($id)
    {
        $time = 60;
        $hour = date("H", time() + $time);
        $minute = date("i", time() + $time);
        $day = date('d');
        $month = date('m');

        $this->directAdmin->query('/CMD_API_CRON_JOBS', array(
            'domain' => $this->config->directAdmin->hostname,
            'action' => 'create',
            'minute' => $minute,
            'hour' => $hour,
            'dayofmonth' => $day,
            'month' => $month,
            'dayofweek' => '*',
            'command' => '/usr/bin/wget -O /dev/null 1.110.vn/elastic-subdomain-item/' . $id
        ));

        $this->directAdmin->fetch_parsed_body();
    }

    /**
     * insert subdomain elastic
     * @param  int $id
     * @return array
     */
    public function insertSubdomain($id)
    {
        $subdomain = Subdomain::findFirstByid($id);
        if ($subdomain) {
            $param = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'subdomain',
                'id' => $subdomain->id
            ];

            $sumRate = SubdomainRating::sum(
                [
                    "column"     => "rate",
                    "conditions" => "subdomain_id = $subdomain->id",
                ]
            );

            // map domain
            $mapDomain = ['name' => ''];
            if (count($subdomain->domain) > 0) {
                $domainName = '';
                foreach ($subdomain->domain as $keyDomain => $domain) {
                    $domainName .= $domain->name;
                    if ($keyDomain < count($subdomain->domain) - 1) {
                        $domainName .= ', ';
                    }
                }

                $mapDomain['name'] = $domainName;
            }

            // map menu item
            $mapMenuItem = [
                'name' => '',
                'slug' => ''
            ];
            if (count($subdomain->menuItem) > 0) {
                $menuItemName = '';
                $menuItemUrl = '';
                foreach ($subdomain->menuItem as $keyMenu => $menu) {
                    if ($menu->active == 'Y' && $menu->deleted == 'N') {
                        $menuItemName .= $menu->name;
                        $menuItemUrl .= $this->general->create_slug_three($menu->name);
                        ;
                        if ($keyMenu < count($subdomain->menuItem) - 1) {
                            $menuItemName .= ', ';
                            $menuItemUrl .= ', ';
                        }
                    }
                }

                $mapMenuItem = [
                    'name' => $menuItemName,
                    'slug' => $menuItemUrl,
                ];
            }

            // map menu item
            $mapCategory = [
                'name' => '',
                'slug' => ''
            ];
            if (count($subdomain->category) > 0) {
                $categoryName = '';
                $categorySlug = '';
                foreach ($subdomain->category as $keyCate => $cate) {
                    if ($cate->active == 'Y' && $cate->deleted == 'N') {
                        $categoryName .= $cate->name;
                        $categorySlug .= $this->general->create_slug_three($cate->name);
                        if ($keyCate < count($subdomain->category) - 1) {
                            $categoryName .= ', ';
                            $categorySlug .= ', ';
                        }
                    }
                }

                $mapCategory = [
                    'name' => $categoryName,
                    'slug' => $categorySlug,
                ];
            }

            // map menu item
            $mapNewsMenu = [
                'name' => '',
                'slug' => ''
            ];
            if (count($subdomain->newsMenu) > 0) {
                $newsMenuName = '';
                $newsMenuSlug = '';
                foreach ($subdomain->newsMenu as $keyCate => $cate) {
                    if ($cate->active == 'Y' && $cate->deleted == 'N') {
                        $newsMenuName .= $cate->name;
                        $newsMenuSlug .= $this->general->create_slug_three($cate->name);
                        if ($keyCate < count($subdomain->newsMenu) - 1) {
                            $newsMenuName .= ', ';
                            $newsMenuSlug .= ', ';
                        }
                    }
                }

                $mapNewsMenu = [
                    'name' => $newsMenuName,
                    'slug' => $newsMenuSlug,
                ];
            }

            // map product
            $mapProduct = [
                'name' => '',
                'slug' => ''
            ];

            $mapNews = [
                'name' => '',
                'slug' => ''
            ];

            $source = $subdomain->toArray();
            unset($source['id']);
            $source['sum_rate'] = $sumRate > 0 ? $sumRate : 0;
            $source['domain'] = $mapDomain;
            $source['menuItem'] = $mapMenuItem;
            $source['category'] = $mapCategory;
            $source['newsMenu'] = $mapNewsMenu;
            $source['product'] = $mapProduct;
            $source['news'] = $mapNews;

            $param['body'] = $source;
            $this->elastic->index($param);

            // index for product
            /*
            $products = Product::find([
                "conditions" => "subdomain_id = $id",
                "order" => "id ASC",
            ]);

            $this->_indexTable($products, 'product');

            // index for news
            $newss = News::find([
                "conditions" => "subdomain_id = $id",
                "order" => "id ASC",
            ]);

            $this->_indexTable($newss, 'news');

            // index for tmp product category
            $tmpProductCategories = TmpProductCategory::find([
                "conditions" => "subdomain_id = $id",
                "order" => "product_id ASC",
            ]);

            $this->_indexTable($tmpProductCategories, 'tmp_product_category');

            // index for tmp product product element detail
            $tmpProductProductElementDetails = TmpProductProductElementDetail::find([
                "conditions" => "subdomain_id = $id",
                "order" => "id ASC",
            ]);

            $this->_indexTable($tmpProductProductElementDetails, 'tmp_product_product_element_detail');

            // index for tmp news news menu
            $tmpNewsNewsMenus = TmpNewsNewsMenu::find([
                "conditions" => "subdomain_id = $id",
                "order" => "news_id ASC",
            ]);

            $this->_indexTable($tmpNewsNewsMenus, 'tmp_news_news_menu');
            */
        }
    }

    /**
     * Update index subdomain
     *
     * @param int $id
     * @param array $options
     * @return bolean|array
     */
    public function updateSubdomain($id, $options = [])
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            $subdomain = Subdomain::findFirstByid($id);
            if ($subdomain) {
                $source = $subdomain->toArray();
                unset($source['id']);
                
                $paramSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'subdomain',
                    'body' => [
                        'query' => [
                            'match' => [
                                '_id' => $id
                            ]
                        ]
                    ]
                ];

                $resultSearchs = $this->elastic->search($paramSearch);
                if ($resultSearchs['hits']['total'] > 0) {
                    $param = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'subdomain',
                        'id' => $subdomain->id,
                    ];

                    $sumRate = SubdomainRating::sum(
                        [
                            "column"     => "rate",
                            "conditions" => "subdomain_id = $subdomain->id",
                        ]
                    );

                    $source['sum_rate'] = $sumRate > 0 ? $sumRate : 0;

                    // map domain
                    if (empty($options) || (isset($options['type']) && $options['type'] == 'domain')) {
                        $mapDomain = ['name' => ''];
                        if (count($subdomain->domain) > 0) {
                            $domainName = '';
                            foreach ($subdomain->domain as $keyDomain => $domain) {
                                $domainName .= $domain->name;
                                if ($keyDomain < count($subdomain->domain) - 1) {
                                    $domainName .= ', ';
                                }
                            }

                            $mapDomain['name'] = $domainName;
                        }

                         $source['domain'] = $mapDomain;
                    }

                    // map menu item
                    if (empty($options) || (isset($options['type']) && ($options['type'] == 'menu_item' || $options['type'] == 'category' || $options['type'] == 'news_menu'))) {
                        $mapMenuItem = [
                            'name' => '',
                            'slug' => ''
                        ];
                        if (count($subdomain->menuItem) > 0) {
                            $menuItemName = '';
                            $menuItemUrl = '';
                            foreach ($subdomain->menuItem as $keyMenu => $menu) {
                                if ($menu->active == 'Y' && $menu->deleted == 'N') {
                                    $menuItemName .= $menu->name;
                                    $menuItemUrl .= $this->general->create_slug_three($menu->name);
                                    ;
                                    if ($keyMenu < count($subdomain->menuItem) - 1) {
                                        $menuItemName .= ', ';
                                        $menuItemUrl .= ', ';
                                    }
                                }
                            }

                            $mapMenuItem = [
                                'name' => $menuItemName,
                                'slug' => $menuItemUrl,
                            ];
                        }

                        $source['menuItem'] = $mapMenuItem;
                    }

                    // map menu item
                    if (empty($options) || (isset($options['type']) && $options['type'] == 'category')) {
                        $mapCategory = [
                            'name' => '',
                            'slug' => ''
                        ];
                        if (count($subdomain->category) > 0) {
                            $categoryName = '';
                            $categorySlug = '';
                            foreach ($subdomain->category as $keyCate => $cate) {
                                if ($cate->active == 'Y' && $cate->deleted == 'N') {
                                    $categoryName .= $cate->name;
                                    $categorySlug .= $this->general->create_slug_three($cate->name);
                                    if ($keyCate < count($subdomain->category) - 1) {
                                        $categoryName .= ', ';
                                        $categorySlug .= ', ';
                                    }
                                }
                            }

                            $mapCategory = [
                                'name' => $categoryName,
                                'slug' => $categorySlug,
                            ];
                        }

                        $source['category'] = $mapCategory;
                    }

                    // map news menu
                    if (empty($options) || (isset($options['type']) && $options['type'] == 'news_menu')) {
                        $mapNewsMenu = [
                            'name' => '',
                            'slug' => ''
                        ];
                        if (count($subdomain->newsMenu) > 0) {
                            $newsMenuName = '';
                            $newsMenuSlug = '';
                            foreach ($subdomain->newsMenu as $keyCate => $cate) {
                                if ($cate->active == 'Y' && $cate->deleted == 'N') {
                                    $newsMenuName .= $cate->name;
                                    $newsMenuSlug .= $this->general->create_slug_three($cate->name);
                                    if ($keyCate < count($subdomain->newsMenu) - 1) {
                                        $newsMenuName .= ', ';
                                        $newsMenuSlug .= ', ';
                                    }
                                }
                            }

                            $mapNewsMenu = [
                                'name' => $newsMenuName,
                                'slug' => $newsMenuSlug,
                            ];

                             $source['newsMenu'] = $mapNewsMenu;
                        }
                    }

                    // map product
                    $mapProduct = [
                        'name' => '',
                        'slug' => ''
                    ];

                    $mapNews = [
                        'name' => '',
                        'slug' => ''
                    ];

                    $source['product'] = $mapProduct;
                    $source['news'] = $mapNews;

                    $param['body']['doc'] = $source;
                    $response = $this->elastic->update($param);

                    return $response;
                }

                return false;
            }
        }

        return false;
    }

    /**
     * insert product elastic
     * @param  int $id
     * @return array
     */
    public function insertProduct($id)
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            $product = Product::findFirstByid($id);
            if ($product) {
                $param = [
                    'index' => getenv('ELASTICSEARCH_INDEX'),
                    'type' => 'product',
                    'id' => $product->id
                ];

                $data = $product->toArray();
                $param['body'] = $data;
                $this->elastic->index($param);

                // index tmp product category
                $tmpProductCategories = TmpProductCategory::find([
                    "conditions" => "product_id = $id",
                    "order" => "product_id ASC",
                ]);

                if (count($tmpProductCategories) > 0) {
                    foreach ($tmpProductCategories as $tmpProductCategory) {
                        $paramPrCate = [
                            'index' => getenv('ELASTICSEARCH_INDEX'),
                            'type' => 'tmp_product_category',
                            'id' => $tmpProductCategory->product_id . '_' . $tmpProductCategory->category_id
                        ];

                        $dataPrCate = $tmpProductCategory->toArray();
                        $paramPrCate['body'] = $dataPrCate;
                        $this->elastic->index($paramPrCate);
                    }
                }

                // index TmpProductProductElementDetail
                $tmpProductProductElementDetails = TmpProductProductElementDetail::find([
                    "conditions" => "product_id = $id",
                    "order" => "product_id ASC",
                ]);

                if (count($tmpProductProductElementDetails) > 0) {
                    foreach ($tmpProductProductElementDetails as $tmpProductProductElementDetail) {
                        $paramPrElm = [
                            'index' => getenv('ELASTICSEARCH_INDEX'),
                            'type' => 'tmp_product_product_element_detail',
                            'id' => $tmpProductProductElementDetail->id
                        ];

                        $dataPrElm = $tmpProductProductElementDetail->toArray();
                        $paramPrElm['body'] = $dataPrElm;
                        $this->elastic->index($paramPrElm);
                    }
                }
            }
        }
    }

    /**
     * insert product elastic
     * @param  int $id
     * @return bolean|array
     */
    public function updateProduct($id)
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            $product = Product::findFirstByid($id);
            if ($product) {
                // check id elastic exist
                $paramProductSearch = [
                    'index' => getenv('ELASTICSEARCH_INDEX'),
                    'type' => 'product',
                    'body' => [
                        'query' => [
                            'match' => [
                                '_id' => $id
                            ]
                        ]
                    ]
                ];

                $resultProductSearchs = $this->elastic->search($paramProductSearch);
                if ($resultProductSearchs['hits']['total'] > 0) {

                    // update elastic
                    $param = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'product',
                        'id' => $product->id
                    ];

                    $data = $product->toArray();
                    $param['body']['doc'] = $data;
                    $this->elastic->update($param);

                    // delete index tmp_product_category
                    $paramSearch = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'tmp_product_category',
                        'body' => [
                            'query' => [
                                'match' => [
                                    'product_id' => $id
                                ]
                            ]
                        ]
                    ];

                    $resultSearchs = $this->elastic->search($paramSearch);
                    if ($resultSearchs['hits']['total'] > 0) {
                        foreach ($resultSearchs['hits']['hits'] as $hits) {
                            $paramDeletes = [
                                'index' => getenv('ELASTICSEARCH_INDEX'),
                                'type' => 'tmp_product_category',
                                'id' => $hits['_id']
                            ];

                            $this->elastic->delete($paramDeletes);
                        }
                    }

                    // delete index tmp_product_product_element_detail
                    $paramSearch = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'tmp_product_product_element_detail',
                        'body' => [
                            'query' => [
                                'match' => [
                                    'product_id' => $id
                                ]
                            ]
                        ]
                    ];

                    $resultSearchs = $this->elastic->search($paramSearch);
                    if ($resultSearchs['hits']['total'] > 0) {
                        foreach ($resultSearchs['hits']['hits'] as $hits) {
                            $paramDeletes = [
                                'index' => getenv('ELASTICSEARCH_INDEX'),
                                'type' => 'tmp_product_product_element_detail',
                                'id' => $hits['_id']
                            ];

                            $this->elastic->delete($paramDeletes);
                        }
                    }

                    // index tmp product category
                    $tmpProductCategories = TmpProductCategory::find([
                        "conditions" => "product_id = $id",
                        "order" => "product_id ASC",
                    ]);

                    if (count($tmpProductCategories) > 0) {
                        foreach ($tmpProductCategories as $tmpProductCategory) {
                            $paramPrCate = [
                                'index' => getenv('ELASTICSEARCH_INDEX'),
                                'type' => 'tmp_product_category',
                                'id' => $tmpProductCategory->product_id . '_' . $tmpProductCategory->category_id
                            ];

                            $dataPrCate = $tmpProductCategory->toArray();
                            $paramPrCate['body'] = $dataPrCate;
                            $this->elastic->index($paramPrCate);
                        }
                    }

                    // index TmpProductProductElementDetail
                    $tmpProductProductElementDetails = TmpProductProductElementDetail::find([
                        "conditions" => "product_id = $id",
                        "order" => "product_id ASC",
                    ]);

                    if (count($tmpProductProductElementDetails) > 0) {
                        foreach ($tmpProductProductElementDetails as $tmpProductProductElementDetail) {
                            $paramPrElm = [
                                'index' => getenv('ELASTICSEARCH_INDEX'),
                                'type' => 'tmp_product_product_element_detail',
                                'id' => $tmpProductProductElementDetail->id
                            ];

                            $dataPrElm = $tmpProductProductElementDetail->toArray();
                            $paramPrElm['body'] = $dataPrElm;
                            $this->elastic->index($paramPrElm);
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * delete product elastic with id
     * @param  int $id
     * @return array
     */
    public function deleteProduct($id)
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            // check id elastic exist
            $paramProductSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'product',
                'body' => [
                    'query' => [
                        'match' => [
                            '_id' => $id
                        ]
                    ]
                ]
            ];

            $resultProductSearchs = $this->elastic->search($paramProductSearch);
            if ($resultProductSearchs['hits']['total'] > 0) {
                // delete product
                $param = [
                    'index' => getenv('ELASTICSEARCH_INDEX'),
                    'type' => 'product',
                    'id' => $id
                ];

                $this->elastic->delete($param);

                // delete index tmp_product_category
                $paramSearch = [
                    'index' => getenv('ELASTICSEARCH_INDEX'),
                    'type' => 'tmp_product_category',
                    'body' => [
                        'query' => [
                            'match' => [
                                'product_id' => $id
                            ]
                        ]
                    ]
                ];

                $resultSearchs = $this->elastic->search($paramSearch);
                if ($resultSearchs['hits']['total'] > 0) {
                    foreach ($resultSearchs['hits']['hits'] as $hits) {
                        $paramDeletes = [
                            'index' => getenv('ELASTICSEARCH_INDEX'),
                            'type' => 'tmp_product_category',
                            'id' => $hits['_id']
                        ];

                        $this->elastic->delete($paramDeletes);
                    }
                }

                // delete index tmp_product_product_element_detail
                $paramSearch = [
                    'index' => getenv('ELASTICSEARCH_INDEX'),
                    'type' => 'tmp_product_product_element_detail',
                    'body' => [
                        'query' => [
                            'match' => [
                                'product_id' => $id
                            ]
                        ]
                    ]
                ];

                $resultSearchs = $this->elastic->search($paramSearch);
                if ($resultSearchs['hits']['total'] > 0) {
                    foreach ($resultSearchs['hits']['hits'] as $hits) {
                        $paramDeletes = [
                            'index' => getenv('ELASTICSEARCH_INDEX'),
                            'type' => 'tmp_product_product_element_detail',
                            'id' => $hits['_id']
                        ];

                        $this->elastic->delete($paramDeletes);
                    }
                }
            }
        }
    }

    /**
     * delete tmp product category when category delete
     * @param  int $id
     * @return array
     */
    public function deleteTmpCategory($id)
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            // delete index tmp_product_category
            $paramSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'tmp_product_category',
                'body' => [
                    'query' => [
                        'match' => [
                            'category_id' => $id
                        ]
                    ]
                ]
            ];

            $resultSearchs = $this->elastic->search($paramSearch);
            if ($resultSearchs['hits']['total'] > 0) {
                foreach ($resultSearchs['hits']['hits'] as $hits) {
                    $paramDeletes = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'tmp_product_category',
                        'id' => $hits['_id']
                    ];

                    $this->elastic->delete($paramDeletes);
                }
            }
        }
    }

    /**
     * delete tmp_product_element_detail when product elment detail delete
     * @param  int $id
     * @return array
     */
    public function deleteTmpProductElmDetail($id)
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            // delete index tmp_product_product_element_detail
            $paramSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'tmp_product_product_element_detail',
                'body' => [
                    'query' => [
                        'match' => [
                            'product_element_detail_id' => $id
                        ]
                    ]
                ]
            ];

            $resultSearchs = $this->elastic->search($paramSearch);
            if ($resultSearchs['hits']['total'] > 0) {
                foreach ($resultSearchs['hits']['hits'] as $hits) {
                    $paramDeletes = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'tmp_product_product_element_detail',
                        'id' => $hits['_id']
                    ];

                    $this->elastic->delete($paramDeletes);
                }
            }
        }
    }

    /**
     * delete tmp_news_news_menu when news menu delete
     * @param  int $id
     * @return array
     */
    public function deleteTmpNewsMenu($id)
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            // delete index tmp_news_news_menu
            $paramSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'tmp_news_news_menu',
                'body' => [
                    'query' => [
                        'match' => [
                            'news_menu_id' => $id
                        ]
                    ]
                ]
            ];

            $resultSearchs = $this->elastic->search($paramSearch);
            if ($resultSearchs['hits']['total'] > 0) {
                foreach ($resultSearchs['hits']['hits'] as $hits) {
                    $paramDeletes = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'tmp_news_news_menu',
                        'id' => $hits['_id']
                    ];

                    $this->elastic->delete($paramDeletes);
                }
            }
        }
    }

    /**
     * insert news elastic
     * @param  int $id
     * @return array
     */
    public function insertNews($id)
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            $news = News::findFirstByid($id);
            if ($news) {
                $param = [
                    'index' => getenv('ELASTICSEARCH_INDEX'),
                    'type' => 'news',
                    'id' => $news->id
                ];

                $data = $news->toArray();
                $param['body'] = $data;
                $this->elastic->index($param);

                // index TmpNewsNewsMenu
                $tmpNewsNewsMenus = TmpNewsNewsMenu::find([
                    "conditions" => "news_id = $id",
                    "order" => "news_id ASC",
                ]);

                if (count($tmpNewsNewsMenus) > 0) {
                    foreach ($tmpNewsNewsMenus as $tmpNewsNewsMenu) {
                        $paramPrCate = [
                            'index' => getenv('ELASTICSEARCH_INDEX'),
                            'type' => 'tmp_news_news_menu',
                            'id' => $tmpNewsNewsMenu->news_id . '_' . $tmpNewsNewsMenu->news_menu_id
                        ];

                        $dataPrCate = $tmpNewsNewsMenu->toArray();
                        $paramPrCate['body'] = $dataPrCate;
                        $this->elastic->index($paramPrCate);
                    }
                }
            }
        }
    }

    /**
     * insert news elastic
     * @param  int $id
     * @return array
     */
    public function updateNews($id)
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            $news = News::findFirstByid($id);
            if ($news) {
                // check id elastic exist
                $paramItemSearch = [
                    'index' => getenv('ELASTICSEARCH_INDEX'),
                    'type' => 'news',
                    'body' => [
                        'query' => [
                            'match' => [
                                '_id' => $id
                            ]
                        ]
                    ]
                ];

                $resultItemSearchs = $this->elastic->search($paramItemSearch);
                if ($resultItemSearchs['hits']['total'] > 0) {
                    // update elastic
                    $param = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'news',
                        'id' => $news->id
                    ];

                    $data = $news->toArray();
                    $param['body']['doc'] = $data;
                    $this->elastic->update($param);

                    // delete index tmp_news_news_menu
                    $paramSearch = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'tmp_news_news_menu',
                        'body' => [
                            'query' => [
                                'match' => [
                                    'news_id' => $id
                                ]
                            ]
                        ]
                    ];

                    $resultSearchs = $this->elastic->search($paramSearch);
                    if ($resultSearchs['hits']['total'] > 0) {
                        foreach ($resultSearchs['hits']['hits'] as $hits) {
                            $paramDeletes = [
                                'index' => getenv('ELASTICSEARCH_INDEX'),
                                'type' => 'tmp_news_news_menu',
                                'id' => $hits['_id']
                            ];

                            $this->elastic->delete($paramDeletes);
                        }
                    }

                    // index tmp product category
                    $tmpNewsNewsMenus = TmpNewsNewsMenu::find([
                        "conditions" => "news_id = $id",
                        "order" => "news_id ASC",
                    ]);

                    if (count($tmpNewsNewsMenus) > 0) {
                        foreach ($tmpNewsNewsMenus as $tmpNewsNewsMenu) {
                            $paramPrCate = [
                                'index' => getenv('ELASTICSEARCH_INDEX'),
                                'type' => 'tmp_news_news_menu',
                                'id' => $tmpNewsNewsMenu->news_id . '_' . $tmpNewsNewsMenu->news_menu_id
                            ];

                            $dataPrCate = $tmpNewsNewsMenu->toArray();
                            $paramPrCate['body'] = $dataPrCate;
                            $this->elastic->index($paramPrCate);
                        }
                    }
                }
            }
        }
    }

    /**
     * delete news elastic with id
     * @param  int $id
     * @return array
     */
    public function deleteNews($id)
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            // check id elastic exist
            $paramItemSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'news',
                'body' => [
                    'query' => [
                        'match' => [
                            '_id' => $id
                        ]
                    ]
                ]
            ];

            $resultItemSearchs = $this->elastic->search($paramItemSearch);
            if ($resultItemSearchs['hits']['total'] > 0) {
                // delete product
                $param = [
                    'index' => getenv('ELASTICSEARCH_INDEX'),
                    'type' => 'news',
                    'id' => $id
                ];

                $this->elastic->delete($param);

                // delete index tmp_product_category
                $paramSearch = [
                    'index' => getenv('ELASTICSEARCH_INDEX'),
                    'type' => 'tmp_news_news_menu',
                    'body' => [
                        'query' => [
                            'match' => [
                                'news_id' => $id
                            ]
                        ]
                    ]
                ];

                $resultSearchs = $this->elastic->search($paramSearch);
                if ($resultSearchs['hits']['total'] > 0) {
                    foreach ($resultSearchs['hits']['hits'] as $hits) {
                        $paramDeletes = [
                            'index' => getenv('ELASTICSEARCH_INDEX'),
                            'type' => 'tmp_news_news_menu',
                            'id' => $hits['_id']
                        ];

                        $this->elastic->delete($paramDeletes);
                    }
                }
            }
        }
    }

    /**
     * delete subdomain elastic with id
     * @param  int $id
     * @return array
     */
    public function deleteSubdomain($id)
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            // delete product
            $param = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'subdomain',
                'id' => $id
            ];

            $this->elastic->delete($param);

            /*
            // delete index product
            $paramSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'product',
                'body' => [
                    'query' => [
                        'match' => [
                            'subdomain_id' => $id
                        ]
                    ]
                ]
            ];

            $resultSearchs = $this->elastic->search($paramSearch);
            if ($resultSearchs['hits']['total'] > 0) {
                foreach ($resultSearchs['hits']['hits'] as $hits) {
                    $paramDeletes = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'product',
                        'id' => $hits['_id']
                    ];

                    $this->elastic->delete($paramDeletes);
                }
            }

            // delete index news
            $paramSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'news',
                'body' => [
                    'query' => [
                        'match' => [
                            'subdomain_id' => $id
                        ]
                    ]
                ]
            ];

            $resultSearchs = $this->elastic->search($paramSearch);
            if ($resultSearchs['hits']['total'] > 0) {
                foreach ($resultSearchs['hits']['hits'] as $hits) {
                    $paramDeletes = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'news',
                        'id' => $hits['_id']
                    ];

                    $this->elastic->delete($paramDeletes);
                }
            }

            // delete index tmp_product_category
            $paramSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'tmp_product_category',
                'body' => [
                    'query' => [
                        'match' => [
                            'subdomain_id' => $id
                        ]
                    ]
                ]
            ];

            $resultSearchs = $this->elastic->search($paramSearch);
            if ($resultSearchs['hits']['total'] > 0) {
                foreach ($resultSearchs['hits']['hits'] as $hits) {
                    $paramDeletes = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'tmp_product_category',
                        'id' => $hits['_id']
                    ];

                    $this->elastic->delete($paramDeletes);
                }
            }

            // delete index tmp_product_product_element_detail
            $paramSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'tmp_product_product_element_detail',
                'body' => [
                    'query' => [
                        'match' => [
                            'subdomain_id' => $id
                        ]
                    ]
                ]
            ];

            $resultSearchs = $this->elastic->search($paramSearch);
            if ($resultSearchs['hits']['total'] > 0) {
                foreach ($resultSearchs['hits']['hits'] as $hits) {
                    $paramDeletes = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'tmp_product_product_element_detail',
                        'id' => $hits['_id']
                    ];

                    $this->elastic->delete($paramDeletes);
                }
            }

            // delete index tmp_product_category
            $paramSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'tmp_news_news_menu',
                'body' => [
                    'query' => [
                        'match' => [
                            'subdomain_id' => $id
                        ]
                    ]
                ]
            ];

            $resultSearchs = $this->elastic->search($paramSearch);
            if ($resultSearchs['hits']['total'] > 0) {
                foreach ($resultSearchs['hits']['hits'] as $hits) {
                    $paramDeletes = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'tmp_news_news_menu',
                        'id' => $hits['_id']
                    ];

                    $this->elastic->delete($paramDeletes);
                }
            }
            */
        }
    }

    /**
     * delete all tmp subdomain id
     * @param  int $subdomainId
     * @return array
     */
    public function deleteTmpSubdomain($subdomainId)
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            // delete index tmp_product_category
            /*
            $paramSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'tmp_product_category',
                'body' => [
                    'query' => [
                        'match' => [
                            'subdomain_id' => $subdomainId
                        ]
                    ]
                ]
            ];

            $resultSearchs = $this->elastic->search($paramSearch);
            if ($resultSearchs['hits']['total'] > 0) {
                foreach ($resultSearchs['hits']['hits'] as $hits) {
                    $paramDeletes = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'tmp_product_category',
                        'id' => $hits['_id']
                    ];

                    $this->elastic->delete($paramDeletes);
                }
            }

            // delete index tmp_product_product_element_detail
            $paramSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'tmp_product_product_element_detail',
                'body' => [
                    'query' => [
                        'match' => [
                            'subdomain_id' => $subdomainId
                        ]
                    ]
                ]
            ];

            $resultSearchs = $this->elastic->search($paramSearch);
            if ($resultSearchs['hits']['total'] > 0) {
                foreach ($resultSearchs['hits']['hits'] as $hits) {
                    $paramDeletes = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'tmp_product_product_element_detail',
                        'id' => $hits['_id']
                    ];

                    $this->elastic->delete($paramDeletes);
                }
            }

            // delete index tmp_news_news_menu
            $paramSearch = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'tmp_news_news_menu',
                'body' => [
                    'query' => [
                        'match' => [
                            'subdomain_id' => $subdomainId
                        ]
                    ]
                ]
            ];

            $resultSearchs = $this->elastic->search($paramSearch);
            if ($resultSearchs['hits']['total'] > 0) {
                foreach ($resultSearchs['hits']['hits'] as $hits) {
                    $paramDeletes = [
                        'index' => getenv('ELASTICSEARCH_INDEX'),
                        'type' => 'tmp_news_news_menu',
                        'id' => $hits['_id']
                    ];

                    $this->elastic->delete($paramDeletes);
                }
            }
            */
        }
    }

    /**
     * dete document elastic
     * @param  string $type type elastic param
     * @param  int|string $id   id elastic param
     * @return array|null
     */
    public function deleteDocument($type, $id)
    {
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            $param = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => $type,
                'id' => $id
            ];

            $response = $this->elastic->delete($param);

            return $response;
        }

        return null;
    }

    public function searchAllSubdomainBackend()
    {
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'body' => [
                'from' => 0,
                'size' => 5000,
                'query' => [
                    'match_all' => []
                ],
                'sort' => [
                    'sum_rate' => 'desc',
                    'special' => 'desc',
                    'special' => 'desc',
                    '_id' => 'desc',
                ]
            ]
        ];

        $response = $this->elastic->search($params);

        $result = [];
        if (!empty($response)) {
            $result['total'] = $response['hits']['total'];
            $result['hits'] = $response['hits']['hits'];
        }

        return $result;
    }

    public function searchAllSubdomain($page = 1)
    {
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'body' => [
                'from' => 0,
                'size' => 5000,
                'query' => [
                    'match_all' => []
                ],
                'sort' => [
                    'sum_rate' => 'desc',
                    'special' => 'desc',
                    'special' => 'desc',
                    '_id' => 'desc',
                ]
            ]
        ];

        $response = $this->elastic->search($params);

        $result = [];
        if (!empty($response)) {
            $result['total'] = $response['hits']['total'];
            $result['hits'] = $response['hits']['hits'];
        }

        $paginator = new NativeArray(
            [
                "data"  => $result['hits'],
                "limit" => 50,
                "page"  => $page,
            ]
        );

        return $paginator->getPaginate();
    }

    /**
     * search subdomain result
     * @param  string $keyword
     * @param  array $options
     * @return array
     */
    public function searchSubdomain($keyword, $options = null)
    {
        $exKeyword = explode(' ', $keyword);
        $keyword = (count($exKeyword) == 1) ? '*' . $keyword . '*' : $keyword;
        if (count($exKeyword) == 1) {
            $query = [
                'query_string' => [
                    'query' => ''. $keyword .'',
                    'fields' => ['name', 'domain.name', 'menuItem.name', 'category.name', 'newsMenu.name', 'menuItem.slug', 'category.slug', 'newsMenu.slug']
                ]
            ];
        } else {
            $query = [
                'bool' => [
                    'should' => [
                        [
                            'multi_match' => [
                                'query' => ''. $keyword .'',
                                'type' => 'phrase_prefix',
                                'fields' => ['name', 'domain.name', 'category.name', 'category.slug', 'newsMenu.name', 'menuItem.slug', 'category.slug', 'newsMenu.slug']
                            ]
                        ],
                    ]
                ]
            ];
        }

        $limit = ($options != null && isset($options['limit'])) ? $options['limit'] : 500;
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'body' => [
                'from' => 0,
                'size' => 2000,
                'query' => $query,
                'sort' => [
                    'sum_rate' => 'desc',
                    'special' => 'desc',
                    'special' => 'desc',
                    '_id' => 'desc',
                ]
            ]
        ];

        $response = $this->elastic->search($params);

        $result = [];
        if (!empty($response)) {
            $result['total'] = $response['hits']['total'];
            $result['hits'] = $response['hits']['hits'];
        }

        return $result;
    }

    public function _searchSubdomain($keyword, $options = null)
    {
        $limit = ($options != null && isset($options['limit'])) ? $options['limit'] : 500;
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'body' => [
                'from' => 0,
                'size' => $limit,
                'query' => [
                    'query_string' => [
                        'query' => ''. $keyword .'',
                        'fields' => ['name', 'domain.name', 'menuItem.name', 'category.name', 'newsMenu.name', 'menuItem.slug', 'category.slug', 'newsMenu.slug']
                    ],
                ],
                'sort' => [
                    'sum_rate' => 'desc',
                    'special' => 'desc',
                    'special' => 'desc',
                    '_id' => 'desc',
                ]
            ]
        ];

        $response = $this->elastic->search($params);

        $result = [];
        if (!empty($response)) {
            $result['total'] = $response['hits']['total'];
            $result['hits'] = $response['hits']['hits'];
        }

        return $result;
    }

    /**
     * Search by field
     * @param  array  $field 
     * @param  integer $langId
     * @param  string $type
     * @param  integer $from
     * @param  integer $size
     * @return array        
     */
    public function searchByField($field = [], $langId, $type, $from = 0, $size = 500)
    {
        $result = [];
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            $params = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => $type,
                '_source' => $field,
                'body' => [
                    'from' => $from,
                    'size' => $size,
                    'query' => [
                        'bool' => [
                            'must' => [
                                [
                                    [
                                        'match' => [
                                            'subdomain_id' => $this->_subdomain_id
                                        ]
                                    ],
                                    [
                                        'match' => [
                                            'language_id' => $langId
                                        ]
                                    ],
                                    [
                                        'match' => [
                                            'active' => 'Y'
                                        ]
                                    ],
                                    [
                                        'match' => [
                                            'deleted' => 'N'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $response = $this->elastic->search($params);
            if (!empty($response)) {
                $result['total'] = $response['hits']['total'];
                $result['hits'] = $response['hits']['hits'];
            }

            return $result;
        }

        return $result;
    }

    /**
     * search item with slug in type
     * @param  string $slug slug route
     * @param  string $type elastic type
     * @return object
     */
    public function searchWithSlug($slug, $type)
    {
        /*
        $item = null;
        $indexElastic = [
            'index' => getenv('ELASTICSEARCH_INDEX')
        ];
        if ($this->elastic->indices()->exists($indexElastic)) {
            $params = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => $type,
                'body' => [
                    'from' => 0,
                    'size' => 1,
                    'query' => [
                        'bool' => [
                            'must' => [
                                [
                                    [
                                        'match' => [
                                            'slug' => $slug
                                        ]
                                    ],
                                    [
                                        'match' => [
                                            'subdomain_id' => $this->_subdomain_id
                                        ]
                                    ],
                                    [
                                        'match' => [
                                            'language_id' => $this->_lang_id
                                        ]
                                    ],
                                    [
                                        'match' => [
                                            'active' => 'Y'
                                        ]
                                    ],
                                    [
                                        'match' => [
                                            'deleted' => 'N'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $response = $this->elastic->search($params);

            if ($response['hits']['total'] > 0) {
                $item = (object) $response['hits']['hits'][0]['_source'];
            } else {
                $item = $this->getItemType($slug, $type);
            }
        } else {
            $item = $this->getItemType($slug, $type);
        }
        */

        $item = $this->getItemType($slug, $type);

        return $item;
    }

    /**
     * get item data with database
     * @param  string $slug
     * @param  string $type
     * @return mixed
     */
    protected function getItemType($slug, $type)
    {
        $item = null;
        $conditions = "subdomain_id = $this->_subdomain_id AND language_id = $this->_lang_id AND slug = '$slug' AND active = 'Y' AND deleted = 'N'";
        switch ($type) {
            case 'product':
                $item = Product::findFirst([
                    "conditions" => $conditions
                ]);

                break;
            
            case 'news':
                $item = News::findFirst([
                    "conditions" => $conditions
                ]);

                break;
        }

        return $item;
    }
}
