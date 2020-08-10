<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\Subdomain;
use Modules\Models\UserHistory;
use Modules\Models\Users;
use Modules\PhalconVn\General;
use Phalcon\Paginator\Adapter\QueryBuilder;
use Phalcon\Http\Response;
use Modules\Auth\UnsafeCrypto;
use Phalcon\Security\Random;

class SubdomainController extends BaseController
{
    protected $_key;

    public function onConstruct()
    {
        parent::onConstruct();
        $this->_key = hex2bin('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f');
        $this->_subdomain = "Modules\Models\Subdomain";
        //so tien bi tru khi tao web moi
        $this->_money_minus_amount = $this->_config_kernel->_cf_kerner_text_money_minus_create_website;
        //so tien website moi duoc cong
        $this->_create_website_amount = $this->_config_kernel->_cf_kerner_text_money_amount_website_create;
        //so tien kich hoat web dau tien
        $this->_active_website_amount = $this->_config_kernel->_cf_kerner_text_money_amount_website_active;
        //so tien kich hoat web thu 2 tro di
        $this->_active_website_second_amount = $this->_config_kernel->_cf_kerner_text_money_amount_website_active_second;
        //gia han
        $this->_renewal_website_amount = $this->_config_kernel->_cf_kerner_text_money_amount_website_renewal;
        // so tien toi thieu trong tai khoan
        $this->_active_website_min_amount = $this->_config_kernel->_cf_kerner_text_money_active_min;
    }

    public function indexAction()
    {
        $titleBar = "Dự án đã thực hiện";
        $breadcrumb = "<li class='active'>$titleBar</li>";
        $all = Subdomain::find([
            "conditions" => "name !='@'",
            "order" => "special DESC, active DESC, id DESC"
        ]);

        /*$hostSubdomain = Subdomain::find([
            "conditions" => "hot = 'Y' AND name !='@' AND suspended = 'N' AND closed = 'N' AND deleted = 'N'",
            "order" => "special DESC, id DESC"
        ]);*/

        $activeSubdomain = Subdomain::find([
            "conditions" => "active = 'Y' AND name !='@' AND suspended = 'N' AND closed = 'N' AND deleted = 'N'",
            "order" => "special DESC, id DESC"
        ]);

        $list = Subdomain::find([
            "conditions" => "name !='@' AND suspended = 'N' AND closed = 'N' AND deleted = 'N'",
            "order" => "special DESC, id DESC"
        ]);


        $this->view->breadcrumb = $breadcrumb;
        // $this->view->host_subdomain = $hostSubdomain;
        $this->view->active_subdomain = $activeSubdomain;
        $this->view->title_bar = $titleBar;
        $this->view->list = $list;
        $this->view->all = $all;
    }

    public function allSubdomainAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $this->view->setTemplateBefore('allSubdomain');
            $page = $this->request->getPost('page');

            $subdomains = $this->modelsManager->createBuilder()
                ->columns("name")
                ->from($this->_subdomain)
                ->where("name != '@'")
                ->orderBy("$this->_subdomain.id DESC");

            $paginator = new QueryBuilder(
                [
                    "builder" => $subdomains,
                    "limit"   => 10,
                    "page"    => $page,
                ]
            );

            $this->view->page = $paginator->getPaginate();
            $this->view->url_page = 'all-subdomain';
            $this->view->pick($this->_getControllerName() . '/allSubdomain');
        }
    }

    public function searchAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $this->view->setTemplateBefore('search');
            $keyword = $this->request->getPost('keyword');
            $list = Subdomain::query()
                ->columns("Modules\Models\Subdomain.*")
                ->leftJoin("Modules\Models\Domain", "d.subdomain_id =  Modules\Models\Subdomain.id", "d")
                ->leftJoin("Modules\Models\MenuItem", "mi.subdomain_id =  Modules\Models\Subdomain.id", "mi")
                ->leftJoin("Modules\Models\Category", "c.subdomain_id =  Modules\Models\Subdomain.id", "c")
                ->leftJoin("Modules\Models\NewsMenu", "ns.subdomain_id =  Modules\Models\Subdomain.id", "ns")
                ->where("(Modules\Models\Subdomain.name LIKE '%". $keyword ."%' OR d.name LIKE '%". $keyword ."%' OR mi.name LIKE '%". $keyword ."%' OR c.name LIKE '%". $keyword ."%' OR ns.name LIKE '%". $keyword ."%')")
                ->groupBy("Modules\Models\Subdomain.id")
                ->orderBy("Modules\Models\Subdomain.id DESC")
                ->execute();
            $this->view->list = $list;
            $this->view->pick($this->_getControllerName() . '/search');
        } else {
            $this->view->disable();
        }
    }

    public function checkNameExistAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $domain = $this->request->getPost('domain');
            $subdomains = Subdomain::findByName($domain);
            $response = new Response();
            $response->setContent(json_encode($subdomains->count()));

            return $response;
        }

        $this->view->disable();
    }

    public function createWebsiteAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $domain = $this->request->getPost('domain', ['striptags', 'string']);
            $username = $this->request->getPost('username', ['striptags', 'string']);
            $password = $this->request->getPost('password');
            $email = $this->request->getPost('email', ['email']);
            $phone = $this->request->getPost('phone');
            $facebook = $this->request->getPost('facebook', ['striptags', 'string']);

            $last_sub = Subdomain::find();
            $last_sub_l = $last_sub->getLast();
            $folder_sort = $last_sub_l->folder_sort + 1;
            if (strlen($folder_sort) < 3) {
                $count = 3 - strlen($folder_sort);
                $txtFolder = '';
                for ($i = 0; $i < $count; $i++) {
                    $txtFolder .= 0;
                }
                $folder = $txtFolder . $folder_sort;
            } else {
                $folder = $folder_sort;
            }

            $subdomain = new Subdomain();
            $general = new General();
            $slug = $general->create_slug($domain);

            $data = [
                'create_id' => 4,
                'name' => $domain,
                'slug' => $slug,
                'folder_sort' => $folder_sort,
                'folder' => $folder,
                'new' => 'Y',
                'active_date' => '0000-00-00 00:00:00',
                'expired_date' => '0000-00-00 00:00:00',
            ];

            $subdomain->assign($data);

            if ($subdomain->save()) {
                $id = $subdomain->id;

                //add user
                $user = new Users();
                $random = new Random();
                $user->assign([
                    'subdomain_id' => $id,
                    'profilesId' => 1,
                    'role' => 2,
                    'sort' => 1,
                    'balance' => $this->_create_website_amount,
                    'email' => $email,
                    'phone' => $phone,
                    'facebook' => strip_tags($facebook),
                    'username' => $username,
                    'password' => $this->security->hash($password),
                    'signup' => 'Y',
                    'token' => $random->base58(24)
                ]);

                if ($user->save()) {
                    //save history
                    $userHistory = new UserHistory();
                    $userHistory->assign([
                        'user_id' => $user->id,
                        'subdomain_id' => $id,
                        'amount' => $this->_create_website_amount,
                        'action' => 4,
                        'summary' => 'số tiền được cộng sẵn',
                    ]);
                    $userHistory->save();
                }

                $created_at = date("Y-m-d H:i:s");

                /*if (!is_dir("files/" . $folder)) {
                    mkdir("files/" . $folder, 0777);
                }*/

                if (!is_dir("files/default/" . $folder)) {
                    mkdir("files/default/" . $folder, 0777);
                }

                if (!is_dir("files/ads/" . $folder)) {
                    mkdir("files/ads/" . $folder, 0777);
                }

                // create news folder
                if (!is_dir("files/news/" . $folder)) {
                    mkdir("files/news/" . $folder, 0777);
                }

                if (!is_dir("files/news/" . $folder . "/" . $this->_date)) {
                    mkdir("files/news/" . $folder . "/" . $this->_date, 0777);
                }

                if (!is_dir("files/news/" . $folder . "/thumb")) {
                    mkdir("files/news/" . $folder . "/thumb", 0777);
                }

                if (!is_dir("files/youtube/" . $folder)) {
                    mkdir("files/youtube/" . $folder, 0777);
                }

                if (!is_dir("files/youtube/" . $folder . "/thumb")) {
                    mkdir("files/youtube/" . $folder . "/thumb", 0777);
                }

                // create product folder
                if (!is_dir("files/product/" . $folder)) {
                    mkdir("files/product/" . $folder, 0777);
                }

                if (!is_dir("files/product/" . $folder . "/" . $this->_date)) {
                    mkdir("files/product/" . $folder . "/" . $this->_date, 0777);
                }


                if (!is_dir("files/category/" . $folder)) {
                    mkdir("files/category/" . $folder, 0777);
                }

                if (!is_dir("bannerhtml")) {
                    mkdir("bannerhtml", 0777);
                }

                if (!is_dir("bannerhtml/" . $folder)) {
                    mkdir("bannerhtml/" . $folder, 0777);
                }

                //creat css folder
                if (!is_dir("assets/css/pages/" . $folder)) {
                    mkdir("assets/css/pages/" . $folder, 0777);
                }

                if (!is_dir("uploads/" . $folder)) {
                    mkdir("uploads/" . $folder, 0777);
                }

                $originCssSub = $this->mainGlobal->getConfigKernel("_css_origin_link");
                $subdomainOrigin = Subdomain::findFirstByName($originCssSub);
                if ($subdomainOrigin) {
                    $this->subdomain_service->copyFolderOrigin($subdomainOrigin->folder, $folder);
                    $this->subdomain_service->createDataOrigin($subdomainOrigin, $subdomain);
                } else {
                    $this->subdomain_service->copyFolderDefautl($folder);
                    $this->subdomain_service->createDataDefault($id);
                }

                // insert elastic
                if (getenv('APP_ENV') == 'production') {
                    $this->elastic_service->addQueuueIndexSubdomainId($id);
                } else {
                    $this->elastic_service->insertSubdomain($id);
                }

                $code = UnsafeCrypto::encrypt($password, $this->_key, true);
                $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $result = ['code' => 1, 'id' => $id, 'url' => $protocol . $domain . '.' . ROOT_DOMAIN . '/token-login?code=' . $code . '&token=' . $user->token];
            } else {
                $mesArr = [];
                foreach ($subdomain->getMessages() as $message) {
                    $mesArr[] = $message;
                }

                $result = ['code' => 0, 'message' => $mesArr];
            }
            
            $response = new Response();
            $response->setContent(json_encode($result));
            return $response;
        }

        $this->view->disable();
    }
}
