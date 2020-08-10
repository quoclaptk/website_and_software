<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\Category;
use Modules\Models\Product;
use Modules\Models\NewsType;
use Modules\Models\NewsCategory;
use Modules\Models\NewsMenu;
use Modules\Models\News;
use Modules\Models\Clip;

class SitemapController extends BaseController
{
    public function onConstruct()
    {
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
    }

    public function indexAction()
    {
        $time = date('c', time());
        $categories = Category::find([
            'columns' => 'slug',
            'conditions' => 'Modules\Models\Category.subdomain_id = '. $this->_subdomain_id .' AND active = "Y" AND deleted = "N"',
            'order' => 'id DESC'
        ]);

        $categoryUrls = [];
        if ($categories->count() > 0) {
            foreach ($categories as $category) {
                $item = [
                    'loc' => $this->tag->site_url_full($category->slug),
                    'lastmod' => $time,
                    'priority' => '0.80'
                ];

                $categoryUrls[] = $item;
            }
        }

        $products = Product::find([
            'columns' => 'slug',
            'conditions' => 'Modules\Models\Product.subdomain_id = '. $this->_subdomain_id .' AND active = "Y" AND deleted = "N"',
            'order' => 'id DESC'
        ]);

        $productUrls = [];
        if ($products->count() > 0) {
            foreach ($products as $product) {
                $item = [
                    'loc' => $this->tag->site_url_full($product->slug),
                    'lastmod' => $time,
                    'priority' => '0.80'
                ];

                $productUrls[] = $item;
            }
        }

        $newsTypes = NewsType::find([
            'columns' => 'slug',
            'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND active = "Y" AND deleted = "N"',
            'order' => 'id DESC'
        ]);

        $newsTypeUrls = [];
        if ($newsTypes->count() > 0) {
            foreach ($newsTypes as $newsType) {
                $item = [
                    'loc' => $this->tag->site_url_full($newsType->slug),
                    'lastmod' => $time,
                    'priority' => '0.80'
                ];

                $newsTypeUrls[] = $item;
            }
        }

        $newsCategories = NewsCategory::find([
            'columns' => 'slug',
            'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND active = "Y" AND deleted = "N"',
            'order' => 'id DESC'
        ]);

        $newsCategoryUrls = [];
        if ($newsCategories->count() > 0) {
            foreach ($newsCategories as $newsCategory) {
                $item = [
                    'loc' => $this->tag->site_url_full($newsCategory->slug),
                    'lastmod' => $time,
                    'priority' => '0.80'
                ];

                $newsCategoryUrls[] = $item;
            }
        }

        $newsMenus = Clip::find([
            'columns' => 'slug',
            'conditions' => 'Modules\Models\Clip.subdomain_id = '. $this->_subdomain_id .' AND active = "Y" AND deleted = "N"',
            'order' => 'id DESC'
        ]);

        $newsMenuUrls = [];
        if ($newsMenus->count() > 0) {
            foreach ($newsMenus as $newsMenu) {
                $item = [
                    'loc' => $this->tag->site_url_full($newsMenu->slug),
                    'lastmod' => $time,
                    'priority' => '0.80'
                ];

                $newsMenuUrls[] = $item;
            }
        }

        $newss = NewsMenu::find([
            'columns' => 'slug',
            'conditions' => 'Modules\Models\NewsMenu.subdomain_id = '. $this->_subdomain_id .' AND active = "Y" AND deleted = "N"',
            'order' => 'id DESC'
        ]);

        $newsUrls = [];
        if ($newss->count() > 0) {
            foreach ($newss as $news) {
                $item = [
                    'loc' => $this->tag->site_url_full($news->slug),
                    'lastmod' => $time,
                    'priority' => '0.80'
                ];

                $newsUrls[] = $item;
            }
        }

        $clips = News::find([
            'columns' => 'slug',
            'conditions' => 'Modules\Models\News.subdomain_id = '. $this->_subdomain_id .' AND active = "Y" AND deleted = "N"',
            'order' => 'id DESC'
        ]);

        $clipUrls = [];
        if ($clips->count() > 0) {
            foreach ($clips as $clip) {
                $item = [
                    'loc' => $this->tag->site_url_full($clip->slug),
                    'lastmod' => $time,
                    'priority' => '0.80'
                ];

                $clipUrls[] = $item;
            }
        }
        
        $doc = new \DomDocument("1.0", "UTF-8");
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;

        // create root node
        $root = $doc->createElement('urlset');
        $urlsetNode = $doc->appendChild($root);
        $urlsetNode->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlsetNode->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $urlsetNode->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

        $urlDefault = array(
            [
                'loc' => $this->tag->site_url_full(),
                'lastmod' => $time,
                'priority' => '1.00'
            ],
            [
                'loc' => $this->tag->site_url_full('san-pham'),
                'lastmod' => $time,
                'priority' => '0.80'
            ],
            [
                'loc' => $this->tag->site_url_full('video'),
                'lastmod' => $time,
                'priority' => '0.80'
            ],
            [
                'loc' => $this->tag->site_url_full('lien-he'),
                'lastmod' => $time,
                'priority' => '0.80'
            ],
            [
                'loc' => $this->tag->site_url_full('gio-hang'),
                'lastmod' => $time,
                'priority' => '0.80'
            ],
            [
                'loc' => $this->tag->site_url_full('dang-ky'),
                'lastmod' => $time,
                'priority' => '0.80'
            ],
            [
                'loc' => $this->tag->site_url_full('dang-nhap'),
                'lastmod' => $time,
                'priority' => '0.80'
            ],
            [
                'loc' => $this->tag->site_url_full('y-kien-khach-hang'),
                'lastmod' => $time,
                'priority' => '0.80'
            ],
            [
                'loc' => $this->tag->site_url_full('du-an-da-thuc-hien'),
                'lastmod' => $time,
                'priority' => '0.80'
            ]
        );

        foreach ($urlDefault as $urls) {
            $occ = $doc->createElement('url');
            $occ = $root->appendChild($occ);
            foreach ($urls as $urlName => $urlValue) {
                $child = $doc->createElement($urlName);
                $child = $occ->appendChild($child);
                $value = $doc->createTextNode($urlValue);
                $value = $child->appendChild($value);
            }
        }

        if (!empty($categoryUrls)) {
            foreach ($categoryUrls as $urls) {
                $occ = $doc->createElement('url');
                $occ = $root->appendChild($occ);
                foreach ($urls as $urlName => $urlValue) {
                    $child = $doc->createElement($urlName);
                    $child = $occ->appendChild($child);
                    $value = $doc->createTextNode($urlValue);
                    $value = $child->appendChild($value);
                }
            }
        }

        if (!empty($productUrls)) {
            foreach ($productUrls as $urls) {
                $occ = $doc->createElement('url');
                $occ = $root->appendChild($occ);
                foreach ($urls as $urlName => $urlValue) {
                    $child = $doc->createElement($urlName);
                    $child = $occ->appendChild($child);
                    $value = $doc->createTextNode($urlValue);
                    $value = $child->appendChild($value);
                }
            }
        }
        
        if (!empty($newsTypeUrls)) {
            foreach ($newsTypeUrls as $urls) {
                $occ = $doc->createElement('url');
                $occ = $root->appendChild($occ);
                foreach ($urls as $urlName => $urlValue) {
                    $child = $doc->createElement($urlName);
                    $child = $occ->appendChild($child);
                    $value = $doc->createTextNode($urlValue);
                    $value = $child->appendChild($value);
                }
            }
        }

        if (!empty($newsCategoryUrls)) {
            foreach ($newsCategoryUrls as $urls) {
                $occ = $doc->createElement('url');
                $occ = $root->appendChild($occ);
                foreach ($urls as $urlName => $urlValue) {
                    $child = $doc->createElement($urlName);
                    $child = $occ->appendChild($child);
                    $value = $doc->createTextNode($urlValue);
                    $value = $child->appendChild($value);
                }
            }
        }

        if (!empty($newsMenuUrls)) {
            foreach ($newsMenuUrls as $urls) {
                $occ = $doc->createElement('url');
                $occ = $root->appendChild($occ);
                foreach ($urls as $urlName => $urlValue) {
                    $child = $doc->createElement($urlName);
                    $child = $occ->appendChild($child);
                    $value = $doc->createTextNode($urlValue);
                    $value = $child->appendChild($value);
                }
            }
        }

        if (!empty($newsUrls)) {
            foreach ($newsUrls as $urls) {
                $occ = $doc->createElement('url');
                $occ = $root->appendChild($occ);
                foreach ($urls as $urlName => $urlValue) {
                    $child = $doc->createElement($urlName);
                    $child = $occ->appendChild($child);
                    $value = $doc->createTextNode($urlValue);
                    $value = $child->appendChild($value);
                }
            }
        }

        if (!empty($clipUrls)) {
            foreach ($clipUrls as $urls) {
                $occ = $doc->createElement('url');
                $occ = $root->appendChild($occ);
                foreach ($urls as $urlName => $urlValue) {
                    $child = $doc->createElement($urlName);
                    $child = $occ->appendChild($child);
                    $value = $doc->createTextNode($urlValue);
                    $value = $child->appendChild($value);
                }
            }
        }
        
        // get completed xml document
        $xml_string = $doc->saveXML() ;
        return $xml_string;
    }

    public function afterExecuteRoute($dispatcher)
    {
        $response = new \Phalcon\Http\Response();
        $response->setHeader('Content-Type', 'application/xml');
        $response->setContent($dispatcher->getReturnedValue());
        $dispatcher->setReturnedValue($response);
    }
}
