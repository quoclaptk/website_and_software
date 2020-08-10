<?php namespace Modules\Backend\Controllers;

use Modules\Models\Subdomain;
use Modules\Models\Domain;
use Modules\Models\Product;
use Modules\Models\News;
use Modules\Forms\CategoryForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Security\Random;
use Phalcon\Image\Adapter\GD;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;
use Modules\PhalconVn\DirectAdmin;

class ElasticController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Elastic search';
        $this->directAdmin = new DirectAdmin();
        $this->directAdmin->connect($this->config->directAdmin->ip, $this->config->directAdmin->port);
        $this->directAdmin->set_login($this->config->directAdmin->username, $this->config->directAdmin->password);
        $this->directAdmin->set_method('get');
        $this->_message = $this->getMessage();
    }

    public function indexAction()
    {
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'my_type',
            'id' => 'my_id',
            'body' => ['testField' => 'abc111', 'testField1' => 'abc12211']
        ];

        $response = $this->elastic->index($params);
        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $this->view->disable();
    }

    public function getAction()
    {
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'my_type',
            'id' => 'my_id'
        ];

        $response = $this->elastic->get($params);
        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'my_type',
            'id' => 'my_id'
        ];

        $source = $this->elastic->getSource($params);
        echo '<pre>';
        print_r($source);
        echo '</pre>';

        $this->view->disable();
    }

    public function searchAction()
    {
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'my_type',
            'body' => [
                'query' => [
                    'match' => [
                        'testField' => 'abc111',
                    ]
                ]
            ]
        ];

        $response = $this->elastic->search($params);
        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $this->view->disable();
    }

    public function deleteDocumentAction()
    {
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'my_type',
            'id' => 'my_id'
        ];

        $response = $this->elastic->delete($params);
        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $this->view->disable();
    }

    /*
    public function deleteIndexAction($index)
    {
        $deleteParams = [
            'index' => $index
        ];
        $response = $this->elastic->indices()->delete($deleteParams);
        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $this->view->disable();
    }
    */

    public function insertAction()
    {
        $subdomains = Subdomain::find();
        foreach ($subdomains as $key => $subdomain) {
            $params['body'][] = array(
                'index' => array(
                    '_index' => getenv('ELASTICSEARCH_INDEX'),
                    '_type' => 'subdomain',
                    '_id' => $subdomain->id,
                ) ,
            );
            $params['body'][] = ['name' => $subdomain->name ];

            $params = [
                'index' => getenv('ELASTICSEARCH_INDEX'),
                'type' => 'subdomain',
                'id' => $subdomain->id,
                'body' => ['name' => $subdomain->name]
            ];

            $response = $this->elastic->index($params);
        }

        $this->view->disable();
    }

    /**
     *
     * Update document
     *
     */
    public function updateAction()
    {
        $param = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'id' => 182,
            'body' => [
                'doc' => [
                    'category' => [
                        'name' => 'zzz'
                    ]
                ]
            ]
        ];

        $response = $this->elastic->update($param);
        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $this->view->disable();
    }

    /**
     *
     * Delete document
     *
     */
    public function deleteAction()
    {
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomains',
            'id' => 79
        ];

        $response = $this->elastic->delete($params);
        echo '<pre>';
        print_r($response);
        echo '</pre>';
        $this->view->disable();
    }

    public function trySearchAction()
    {
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'body' => [
                'query' => [
                    'match' => [
                        'name' => 'demo11',
                    ]
                ]
            ]
        ];

        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [ 'match' => [ 'name' => 'demo38' ] ],
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->elastic->search($params);
        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $this->view->disable();
    }

    public function indexNormalAction()
    {
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                ],
                'mappings' => [
                    'my_type' => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            'first_name' => [
                                'type' => 'string',
                                'analyzer' => 'standard'
                            ],
                            'age' => [
                                'type' => 'integer'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Create the index with mappings and settings now
        $response = $this->elastic->indices()->create($params);

        $this->view->disable();
    }

    public function bulkAction()
    {
        $subdomains = Subdomain::find();

        foreach ($subdomains as $key => $subdomain) {
            $params['body'][] = [
                'index' => [
                    '_index' => getenv('ELASTICSEARCH_INDEX'),
                    '_type' => 'subdomains',
                    '_id' => $subdomain->id
                ]
            ];

            $domainMap = [];
            if (count($subdomain->domain) > 0) {
                $dmn = '';
                foreach ($subdomain->domain as $keyDm => $domain) {
                    $dmn .= $domain->name;
                    if ($keyDm < count($subdomain->domain) - 1) {
                        $dmn .= ', ';
                    }
                }

                $domainMap['name'] = $dmn;
            }

            $source = [
                'name' => $subdomain->name,
                'active' => $subdomain->active,
                'sort' => $subdomain->sort,
            ];

            if (!empty($domainMap)) {
                $source['domain'] = $domainMap;
            }

            $params['body'][] = $source;
        }

        $responses = $this->elastic->bulk($params);

        $this->view->disable();
    }

    public function searchBulkAction()
    {
        // query like
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'body' => [
                'from' => 0,
                'size' => 2000,
                'filter' => [
                    'bool' => [
                        'must' => [
                            'bool' => [
                                'should' => [
                                    'bool' => [
                                        'must' => [
                                            'wildcard' => [
                                                'name' => '*26*',
                                            ]
                                        ]
                                    ],
                                    'bool' => [
                                        'must' => [
                                            'wildcard' => [
                                                'domain.name' => '*abc*',
                                            ]
                                        ],
                                    ]
                                ],
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->elastic->search($params);

        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $this->view->disable();
    }

    public function searchMultiAction()
    {
        // query like
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'body' => [
                'from' => 0,
                'size' => 2000,
                'query' => [
                    'query_string' => [
                        'query' => '*toyota*',
                        'fields' => ['name', 'domain.name', 'menuItem.name', 'category.name', 'newsMenu.name', 'menuItem.slug', 'category.slug', 'newsMenu.slug']
                    ]
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

        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $this->view->disable();
    }

    public function searchMultiMatchAction()
    {
        // query like
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'body' => [
                'from' => 0,
                'size' => 2000,
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'multi_match' => [
                                    'query' => 'dong ho',
                                    'type' => 'phrase_prefix',
                                    'fields' => ['name', 'domain.name', 'category.name', 'category.slug', 'newsMenu.name', 'menuItem.slug', 'category.slug', 'newsMenu.slug']
                                ]
                            ],
                        ]
                    ]
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

        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $this->view->disable();
    }

    public function searchPrefixAction()
    {
        // query like
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'body' => [
                'from' => 0,
                'size' => 2000,
                'query' => [
                    'prefix' => [
                        'name' => '26',
                    ],
                ]
            ]
        ];

        $response = $this->elastic->search($params);

        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $this->view->disable();
    }

    public function testSearchAction()
    {
        $keyword = '*toyota*';
        echo $keyword;
        $result = $this->elastic_service->searchSubdomain($keyword);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
        $this->view->disable();
    }

    public function searchAllAction()
    {
        // query like
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'body' => [
                'from' => 0,
                'size' => 5000,
                'query' => [
                    'match_all' => []
                ]
            ]
        ];

        $response = $this->elastic->search($params);

        $results = $response['hits']['hits'];

        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $this->view->disable();
    }

    /**
     * get all data in type elastic
     * @return [type] [description]
     */
    public function getALlAction()
    {
        $index = $this->request->get('index');
        $type = $this->request->get('type');
        $params = [
            'index' => $index,
            'type' => $type,
            'body' => [
                'from' => 0,
                'size' => 2000,
                'query' => [
                    'match_all' => []
                ]
            ]
        ];

        $response = $this->elastic->search($params);

        $results = $response['hits']['hits'];

        echo '<pre>';
        print_r($response);
        echo '</pre>';

        $this->view->disable();
    }

    public function updateAllAction()
    {
        // query like
        $params = [
            'index' => getenv('ELASTICSEARCH_INDEX'),
            'type' => 'subdomain',
            'body' => [
                'from' => 0,
                'size' => 5000,
                'query' => [
                    'match_all' => []
                ]
            ]
        ];

        $response = $this->elastic->search($params);

        $results = $response['hits']['hits'];

        foreach ($results as $result) {
            $id = $result['_id'];
            $subdomain = Subdomain::findFirstById($id);
            if (!$subdomain) {
                $param = [
                    'index' => getenv('ELASTICSEARCH_INDEX'),
                    'type' => 'subdomain',
                    'id' => $id
                ];

                $this->elastic->delete($param);
            }
        }

        $this->view->disable();
    }

    public function createCronJobElasticSubdomainAction()
    {
        $subdomains = Subdomain::find([
            "columns" => "id",
            "conditions" => "name != '@'",
        ]);

        $count = ceil(count($subdomains) / 200);
        for ($i = 0; $i < $count; $i++) {
            $time = $i * 120;
            $hour = date("H", time() + $time);
            $minute = date("i", time() + $time);
            $day = date('d', time() + $time);
            $month = date('m', time() + $time);

            $this->directAdmin->query('/CMD_API_CRON_JOBS', array(
                'domain' => $this->config->directAdmin->hostname,
                'action' => 'create',
                'minute' => $minute,
                'hour' => $hour,
                'dayofmonth' => $day,
                'month' => $month,
                'dayofweek' => '*',
                'command' => '/usr/bin/wget -O /dev/null 110.vn/elastic-subdomain?page=' . $i
            ));

            $this->directAdmin->fetch_parsed_body();
        }

        $this->view->disable();
    }

    public function createCronJobElasticAction()
    {
        $subdomains = Subdomain::find([
            "columns" => "id",
            "order" => "special DESC, active DESC, id DESC",
        ]);

        $count = ceil(count($subdomains) / 4);
        for ($i = 0; $i < $count; $i++) {
            $time = $i * 120;
            $hour = date("H", time() + $time);
            $minute = date("i", time() + $time);
            $day = date('d', time() + $time);
            $month = date('m', time() + $time);

            $this->directAdmin->query('/CMD_API_CRON_JOBS', array(
                'domain' => $this->config->directAdmin->hostname,
                'action' => 'create',
                'minute' => $minute,
                'hour' => $hour,
                'dayofmonth' => $day,
                'month' => $month,
                'dayofweek' => '*',
                'command' => '/usr/bin/wget -O /dev/null 1.110.vn/elastic-data?page=' . $i
                // 'command' => '/usr/bin/wget -O /dev/null 1.110.vn/elastic-subdomain?page=' . $i
            ));

            $this->directAdmin->fetch_parsed_body();
        }

        $this->view->disable();
    }

    public function createCronJobElasticProductAction()
    {
        $products = Product::find([
            "columns" => "id",
        ]);

        $count = ceil(count($products) / 500);
        for ($i = 0; $i < $count; $i++) {
            $time = $i * 120;
            $hour = date("H", time() + $time);
            $minute = date("i", time() + $time);
            $day = date('d', time() + $time);
            $month = date('m', time() + $time);

            $this->directAdmin->query('/CMD_API_CRON_JOBS', array(
                'domain' => $this->config->directAdmin->hostname,
                'action' => 'create',
                'minute' => $minute,
                'hour' => $hour,
                'dayofmonth' => $day,
                'month' => $month,
                'dayofweek' => '*',
                'command' => '/usr/bin/wget -O /dev/null 1.110.vn/elastic-data?page=' . $i
                // 'command' => '/usr/bin/wget -O /dev/null 1.110.vn/elastic-subdomain?page=' . $i
            ));

            $this->directAdmin->fetch_parsed_body();
        }

        $this->view->disable();
    }

    public function createCronJobElasticNewsAction()
    {
        $newss = News::find([
            "columns" => "id",
        ]);

        $count = ceil(count($newss) / 500);
        for ($i = 0; $i < $count; $i++) {
            $time = $i * 120;
            $hour = date("H", time() + $time);
            $minute = date("i", time() + $time);
            $day = date('d', time() + $time);
            $month = date('m', time() + $time);

            $this->directAdmin->query('/CMD_API_CRON_JOBS', array(
                'domain' => $this->config->directAdmin->hostname,
                'action' => 'create',
                'minute' => $minute,
                'hour' => $hour,
                'dayofmonth' => $day,
                'month' => $month,
                'dayofweek' => '*',
                'command' => '/usr/bin/wget -O /dev/null 1.110.vn/elastic-data?page=' . $i
                // 'command' => '/usr/bin/wget -O /dev/null 1.110.vn/elastic-subdomain?page=' . $i
            ));

            $this->directAdmin->fetch_parsed_body();
        }

        $this->view->disable();
    }
}
