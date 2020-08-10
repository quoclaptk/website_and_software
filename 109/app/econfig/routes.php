<?php

use Phalcon\Mvc\Router\Annotations as RouterAnnotations;
use Modules\PhalconVn\MainGlobal;
use Modules\Models\Category;
use Modules\Models\Product;
use Modules\Models\NewsType;
use Modules\Models\NewsCategory;
use Modules\Models\NewsMenu;
use Modules\Models\LandingPage;
use Modules\Models\News;
use Modules\Models\Clip;
use Modules\Models\Subdomain;
use Modules\Models\TmpSubdomainLanguage;
use Modules\Repositories\CategoryRepository;
use Modules\Repositories\NewsMenuRepository;
use Modules\Repositories\LandingPageRepository;
use Modules\Repositories\ProductRepository;
use Modules\Repositories\NewsRepository;
use Modules\Repositories\ClipRepository;

$mainGlobal = MainGlobal::getInstance();
$subdomainId = $mainGlobal->getDomainId();
$tmpSubdomainLanguages = TmpSubdomainLanguage::findBySubdomainId($subdomainId);

// category reposiotry
$categoryRepository = new CategoryRepository(new Category());

// newsMenu reposiotry
$newsMenuRepository = new NewsMenuRepository(new NewsMenu());

// landingPage reposiotry
$landingPageRepository = new LandingPageRepository(new LandingPage());

// product reposiotry
$productRepository = new ProductRepository(new Product());

// news reposiotry
$newsRepository = new NewsRepository(new News());

// clip reposiotry
$clipRepository = new ClipRepository(new Clip());

$categories = $categoryRepository->getSlugForRoute(1);
$newsMenu = $newsMenuRepository->getSlugForRoute(1);
$landingPages = $landingPageRepository->getSlugForRoute(1);
$news = $newsRepository->getSlugForRoute(1);
$clip = $clipRepository->getSlugForRoute(1);

$newsType = NewsType::find([
    "columns" => "slug",
    "conditions" => "subdomain_id = $subdomainId AND active='Y' AND deleted = 'N'"
]);

$newsCategory = NewsCategory::find([
    "columns" => "slug",
    "conditions" => "subdomain_id = $subdomainId AND active='Y' AND deleted = 'N'"
]);

$router = new Phalcon\Mvc\Router(false);

$router->removeExtraSlashes(true);
$router->setDefaultModule("frontend");
$router->setDefaultAction("index");
$router->setDefaultController("index");

$router->add("/([a-zA-Z0-9\_\-]+)", array(
    'controller' => 'product',
    'action' => 'detail',
    'params' => 1
));

$router->add("/auth-login", array(
    'module' => 'backend',
    'controller' => 'index',
    'action' => 'login',
));

//route frontend
$router->add(
    "/",
    array(
        'controller' => 'index',
        'action'     => 'index'
    )
);

// Matches '/es/news'
$router->add(
    '/{language:[a-z]{2}}',
    [
        'module' => 'frontend',
        'controller' => 'index',
        'action'     => 'index',
    ]
);


$router->add(
    '/{language:[a-z]{2}}/{controller:[a-z]+}',
    [
        'module' => 'frontend',
        'controller' => 2,
        'action'     => 'index',
    ]
);

$router->add(
    '/{language:[a-z]{2}}/{controller:[a-z]+}/([0-9]+)',
    [
        'module' => 'frontend',
        'controller' => 2,
        'action'     => 'index',
        'page' => 3
    ]
);

if (count($categories) > 0) {
    foreach ($categories as $row) {
        $slug = $row->slug;
        if (!empty($slug)) {
            $router->add("/$slug", array(
                'controller' => 'product',
                'action' => 'category',
                'params' => $slug
            ));
            $router->add("/$slug/([0-9]+)", array(
                'controller' => 'product',
                'action' => 'category',
                'params' => $slug,
                'int' => 1
            ));
        }
    }
}

if (count($newsType) > 0) {
    foreach ($newsType as $row) {
        $slug = $row->slug;
        if (!empty($slug)) {
            $router->add("/$slug", array(
                'controller' => 'news',
                'action' => 'type',
                'params' => $slug
            ));
            $router->add("/$slug/([0-9]+)", array(
                'controller' => 'news',
                'action' => 'type',
                'params' => $slug,
                'int' => 1
            ));
        }
    }
}

if (count($newsCategory) > 0) {
    foreach ($newsCategory as $row) {
        $slug = $row->slug;
        if (!empty($slug)) {
            $router->add("/$slug", array(
                'controller' => 'news',
                'action' => 'category',
                'params' => $slug
            ));
            $router->add("/$slug/([0-9]+)", array(
                'controller' => 'news',
                'action' => 'category',
                'params' => $slug,
                'int' => 1
            ));
        }
    }
}

if (count($landingPages) > 0) {
    foreach ($landingPages as $row) {
        $slug = $row->slug;
        if (!empty($slug)) {
            $router->add("/$slug", array(
                'controller' => 'landing_page',
                'action' => 'detail',
                'params' => $slug
            ));
        }
    }
}

if (count($newsMenu) > 0) {
    foreach ($newsMenu as $row) {
        $slug = $row->slug;
        if (!empty($slug)) {
            $router->add("/$slug", array(
                'controller' => 'news',
                'action' => 'menu',
                'params' => $slug
            ));
            $router->add("/$slug/([0-9]+)", array(
                'controller' => 'news',
                'action' => 'menu',
                'params' => $slug,
                'int' => 1
            ));
        }
    }
}

if (count($news) > 0) {
    foreach ($news as $row) {
        $slug = $row->slug;
        if (!empty($slug)) {
            $router->add("/$slug", array(
                'controller' => 'news',
                'action' => 'detail',
                'params' => $slug
            ));
        }
    }
}

if (count($clip) > 0) {
    foreach ($clip as $row) {
        $slug = $row->slug;
        if (!empty($slug)) {
            $router->add("/$slug", array(
                'controller' => 'video',
                'action' => 'detail',
                'params' => $slug
            ));
            $router->add("/$slug/([0-9]+)", array(
                'controller' => 'video',
                'action' => 'detail',
                'params' => $slug,
                'int' => 1
            ));
        }
    }
}


// route language
if (count($tmpSubdomainLanguages) > 0) {
    foreach ($tmpSubdomainLanguages as $tmp) {
        $langCode = $tmp->language->code;
        $langId = $tmp->language_id;
        if ($langCode != 'vi') {
            $router->add("/{language:[a-z]{2}}/search", array(
                'controller' => 'product',
                'action' => 'search',
            ));

            $products = $productRepository->getSlugForRoute($langId);
            $categories = $categoryRepository->getSlugForRoute($langId);
            $newsMenu = $newsMenuRepository->getSlugForRoute($langId);
            $landingPages = $landingPageRepository->getSlugForRoute($langId);
            $news = $newsRepository->getSlugForRoute($langId);
            $clip = $clipRepository->getSlugForRoute($langId);

            if (count($products) > 0) {
                foreach ($products as $row) {
                    $slug = $row->slug;
                    if (!empty($slug)) {
                        $router->add(
                            "/{language:[a-z]{2}}/$slug",
                            [
                                'controller' => 'product',
                                'action'     => 'detail',
                                'slug'     => $slug
                            ]
                        );
                    }
                }
            }

            
            if (count($categories) > 0) {
                foreach ($categories as $row) {
                    $slug = $row->slug;
                    if (!empty($slug)) {
                        $router->add(
                            "/{language:[a-z]{2}}/$slug",
                            [
                                'controller' => 'product',
                                'action'     => 'category',
                                'slug'     => $slug
                            ]
                        );
                        $router->add(
                            "/{language:[a-z]{2}}/$slug/([0-9]+)",
                            [
                                'controller' => 'product',
                                'action'     => 'category',
                                'slug'     => $slug,
                                'page' => 2
                            ]
                        );
                    }
                }
            }
                
            if (count($landingPages) > 0) {
                foreach ($landingPages as $row) {
                    $slug = $row->slug;
                    if (!empty($slug)) {
                        $router->add("/{language:[a-z]{2}}/$slug", array(
                            'controller' => 'landing_page',
                            'action' => 'detail',
                            'params' => $slug
                        ));
                    }
                }
            }

            if (count($newsMenu) > 0) {
                foreach ($newsMenu as $row) {
                    $slug = $row->slug;
                    if (!empty($slug)) {
                        $router->add("/{language:[a-z]{2}}/$slug", array(
                            'controller' => 'news',
                            'action' => 'menu',
                            'params' => $slug
                        ));

                        $router->add(
                            "/{language:[a-z]{2}}/$slug/([0-9]+)",
                            [
                                'controller' => 'news',
                                'action'     => 'menu',
                                'slug'     => $slug,
                                'page' => 2
                            ]
                        );
                    }
                }
            }

            if (count($news) > 0) {
                foreach ($news as $row) {
                    $slug = $row->slug;
                    if (!empty($slug)) {
                        $router->add("/{language:[a-z]{2}}/$slug", array(
                            'controller' => 'news',
                            'action' => 'detail',
                            'params' => $slug
                        ));

                        $router->add(
                            "/{language:[a-z]{2}}/$slug/([0-9]+)",
                            [
                                'controller' => 'news',
                                'action'     => 'detail',
                                'slug'     => $slug,
                                'page' => 2
                            ]
                        );
                    }
                }
            }

            if (count($clip) > 0) {
                foreach ($clip as $row) {
                    $slug = $row->slug;
                    if (!empty($slug)) {
                        $router->add("/{language:[a-z]{2}}/$slug", array(
                            'controller' => 'video',
                            'action' => 'detail',
                            'params' => $slug
                        ));

                        $router->add(
                            "/{language:[a-z]{2}}/$slug/([0-9]+)",
                            [
                                'controller' => 'video',
                                'action'     => 'detail',
                                'slug'     => $slug,
                                'page' => 2
                            ]
                        );
                    }
                }
            }
        }
    }
}

$router->add("/trang-chu", array(
    'controller' => 'index',
    'action' => 'index',
));

$router->add("/trang-chu/([0-9]+)", array(
    'controller' => 'index',
    'action' => 'index',
    'int' => 1
));

$router->add("/san-pham", array(
    'controller' => 'product',
    'action' => 'index',
));

$router->add("/san-pham/([0-9]+)", array(
    'controller' => 'product',
    'action' => 'index',
    'int' => 1
));

$router->add("/gio-hang", array(
    'controller' => 'cart',
    'action' => 'index',
));

$router->add("/add-to-cart", array(
    'controller' => 'cart',
    'action' => 'insert',
));

$router->add("/update-cart", array(
    'controller' => 'cart',
    'action' => 'update',
));

$router->add("/delete-cart", array(
    'controller' => 'cart',
    'action' => 'delete',
));

$router->add("/video", array(
    'controller' => 'video',
    'action' => 'index',
));

$router->add("/video/([0-9]+)", array(
    'controller' => 'video',
    'action' => 'index',
    'int' => 1
));

$router->add("/thanh-toan", array(
    'controller' => 'checkout',
    'action' => 'index',
));

$router->add("/dat-hang-thanh-cong", array(
    'controller' => 'checkout',
    'action' => 'success',
));

$router->add("/lien-he", array(
    'controller' => 'contact',
    'action' => 'index',
));

$router->add("/send-customer-message", array(
    'controller' => 'customer_message',
    'action' => 'send',
));

$router->add("/send-form-info", array(
    'controller' => 'form_item',
    'action' => 'send',
));

$router->add("/y-kien-khach-hang", array(
    'controller' => 'comment',
    'action' => 'index',
));

$router->add("/send-customer-comment", array(
    'controller' => 'comment',
    'action' => 'send',
));

$router->add("/send-contact", array(
    'controller' => 'contact',
    'action' => 'sendForm',
));

$router->add("/admin-login", array(
    'controller' => 'index',
    'action' => 'adminLogin',
));

$router->add("/delete-all-cache", array(
    'controller' => 'index',
    'action' => 'deleteAllCache',
));

$router->add("/system-login", array(
    'controller' => 'users',
    'action' => 'systemLogin',
));

$router->add("/token-login", array(
    'controller' => 'users',
    'action' => 'tokenLogin',
));

$router->add("/user-exist", array(
    'controller' => 'users',
    'action' => 'checkUsernameExist',
));

$router->add("/user-email-exist", array(
    'controller' => 'users',
    'action' => 'checkEmailExist',
));

$router->add("/subdomain-exist", array(
    'controller' => 'subdomain',
    'action' => 'checkNameExist',
));

$router->add("/subdomain-create", array(
    'controller' => 'subdomain',
    'action' => 'createWebsite',
));

$router->add("/tim-kiem", array(
    'controller' => 'product',
    'action' => 'search',
));

$router->add("/add-to-server/([0-9]+)", array(
    'controller' => 'ajax',
    'action' => 'addToServer',
    'id' => 1
));

$router->add("/delete-sub-on-server/([a-zA-Z0-9\_\-]+)", array(
    'controller' => 'ajax',
    'action' => 'deleteSubOnServer',
    'name' => 1
));

$router->add("/delete-cronjobs", array(
    'controller' => 'ajax',
    'action' => 'deleteCronJobs'
));

$router->add("/test-luu-redis", array(
    'controller' => 'ajax',
    'action' => 'ajaxRedis'
));

$router->add("/dang-ky", array(
    'controller' => 'member',
    'action' => 'signup',
));

$router->add("/dang-nhap", array(
    'controller' => 'member',
    'action' => 'login',
));

$router->add("/dang-xuat", array(
    'controller' => 'member',
    'action' => 'logout',
));

$router->add("/tai-khoan", array(
    'controller' => 'member',
    'action' => 'account',
));

$router->add("/tai-khoan/doi-mat-khau", array(
    'controller' => 'member',
    'action' => 'changePassword',
));

$router->add("/tai-khoan/lich-su-don-hang", array(
    'controller' => 'member',
    'action' => 'historyOrder',
));

$router->add("/tai-khoan/lich-su-don-hang/([0-9]+)", array(
    'controller' => 'member',
    'action' => 'historyOrderDetail',
    'id' => 1
));

// router account lang
$router->add("/{language:[a-z]{2}}/signup", array(
    'controller' => 'member',
    'action' => 'signup',
));

$router->add("/{language:[a-z]{2}}/login", array(
    'controller' => 'member',
    'action' => 'login',
));

$router->add("/{language:[a-z]{2}}/logout", array(
    'controller' => 'member',
    'action' => 'logout',
));

$router->add("/{language:[a-z]{2}}/account", array(
    'controller' => 'member',
    'action' => 'account',
));

$router->add("/{language:[a-z]{2}}/account/changePassword", array(
    'controller' => 'member',
    'action' => 'changePassword',
));

$router->add("/{language:[a-z]{2}}/account/historyOrder", array(
    'controller' => 'member',
    'action' => 'historyOrder',
));

$router->add("/{language:[a-z]{2}}/account/historyOrder/([0-9]+)", array(
    'controller' => 'member',
    'action' => 'historyOrderDetail',
    'id' => 2
));

$router->add("/{language:[a-z]{2}}/orderSuccess", array(
    'controller' => 'checkout',
    'action' => 'success',
));

$router->add("/newsletter", array(
    'controller' => 'ajax',
    'action' => 'newsletter',
));

$router->add("/send-mail", array(
    'controller' => 'ajax',
    'action' => 'sendMail',
));

$router->add("/sitemap.xml", array(
    'controller' => 'sitemap',
    'action' => 'index',
));

$router->add("/robots.txt", array(
    'controller' => 'robots',
    'action' => 'index',
));

$router->add("/du-an-da-thuc-hien", array(
    'controller' => 'project',
    'action' => 'index',
));

$router->add("/subdomain-search", array(
    'controller' => 'project',
    'action' => 'search',
));

$router->add("/all-news", array(
    'controller' => 'news',
    'action' => 'allNews',
));

$router->add("/all-product", array(
    'controller' => 'product',
    'action' => 'allProduct',
));

$router->add("/all-subdomain", array(
    'controller' => 'project',
    'action' => 'allSubdomain',
));

$router->add("/ajax-subdomain", array(
    'controller' => 'project',
    'action' => 'ajaxSubdomain',
));

$router->add("/ajax-product-load-product-photo", array(
    'controller' => 'product',
    'action' => 'loadProductPhoto'
));

$router->add("/ajax-product-get-product-element-info", array(
    'controller' => 'product',
    'action' => 'getProductElementInfo'
));

$router->add("/elastic-subdomain", array(
    'controller' => 'elastic',
    'action' => 'indexSubdomain',
));

$router->add("/elastic-subdomain-item/([0-9]+)", array(
    'controller' => 'elastic',
    'action' => 'indexSubdomainId',
    'id' => 1
));

$router->add("/elastic-data", array(
    'controller' => 'elastic',
    'action' => 'indexData',
));

$router->add(
    "/cronjob/:action/:params",
    array(
        "controller" => 'cronjob',
        "action"     => 1,
        "params"     => 2,
    )
);

$router->add("/hi", array(
    'module' => 'backend',
    'controller' => 'index',
    'action' => 'index',

));

$router->add('/hi/:controller', array(
    'module' => 'backend',
    'controller' => 1,
    'action' => 'index',
));

$router->add('/hi/:controller/:action', array(
    'module' => 'backend',
    'controller' => 1,
    'action' => 2,
));
//Define a route
$router->add(
    "/hi/:controller/:action/:params",
    array(
        'module' => 'backend',
        "controller" => 1,
        "action"     => 2,
        "params"     => 3,
    )
);

$router->notFound(array(
    'controller'  => 'index',
    'action'      => 'notfound'
));


return $router;
