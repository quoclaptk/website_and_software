<?php 

namespace Modules\Backend\Controllers;

use Modules\Models\Domain;
use Modules\Models\NewsType;
use Modules\Models\NewsCategory;
use Modules\Models\NewsMenu;
use Modules\Models\Setting;
use Modules\Models\Subdomain;
use Modules\Models\Users;
use Modules\Models\Menu;
use Modules\Models\MenuItem;
use Modules\Models\ModuleGroup;
use Modules\Models\ModuleItem;
use Modules\Models\TmpPositionModuleItem;
use Modules\Models\TmpLayoutModule;
use Modules\Models\BannerType;
use Modules\Models\Banner;
use Modules\Models\Layout;
use Modules\Models\TmpBannerBannerType;
use Modules\Models\Posts;
use Modules\Models\Category;
use Modules\Models\Product;
use Modules\Models\ProductPhoto;
use Modules\Models\ProductContent;
use Modules\Models\ProductDetail;
use Modules\Models\ProductElement;
use Modules\Models\ProductElementDetail;
use Modules\Models\TmpProductCategory;
use Modules\Models\TmpProductProductElementDetail;
use Modules\Models\TmpSubdomainLanguage;
use Modules\Models\ConfigCore;
use Modules\Models\ConfigItem;
use Modules\Models\Contact;
use Modules\Models\Orders;
use Modules\Models\Background;
use Modules\Models\Clip;
use Modules\Models\LayoutConfig;
use Modules\Models\News;
use Modules\Models\TmpSubdomainUser;
use Modules\Models\TmpNewsNewsMenu;
use Modules\Models\TmpNewsNewsCategory;
use Modules\Models\TmpProductFormItem;
use Modules\Models\WordCore;
use Modules\Models\WordItem;
use Modules\Models\UserHistory;
use Modules\Models\UserHistoryTransfer;
use Modules\Models\CustomerComment;
use Modules\Models\LandingPage;
use Modules\Models\TmpLandingModule;
use Modules\Models\SubdomainRating;
use Modules\Forms\DomainForm;
use Modules\Forms\SubdomainForm;
use Modules\PhalconVn\General;
use Modules\PhalconVn\DirectAdmin;
use Phalcon\Tag;
use Phalcon\Text;
use Phalcon\Security\Random;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use Phalcon\Mvc\View;
use Phalcon\Http\Response;
use Phalcon\Paginator\Adapter\NativeArray;
use Modules\Mail\MyPHPMailer;

/**
 * Modules\Controllers\UsersController
 *
 * CRUD to manage users
 */
class SubdomainController extends BaseController
{
    private $_date;
    private $_sub_folder;
    private $_layout_id;

    public function onConstruct()
    {
        parent::onConstruct();
        $this->_date = date('d-m-Y', time());
        $this->_sub_folder = $this->_get_subdomainFolder();
        $this->_layout_id  = 2;
        $this->view->module_name = 'Quản lý tên miền';
        $this->directAdmin = new DirectAdmin();
        $this->directAdmin->connect($this->config->directAdmin->ip, $this->config->directAdmin->port);
        $this->directAdmin->set_login($this->config->directAdmin->username, $this->config->directAdmin->password);
        $this->directAdmin->set_method('get');
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
        // so tien kich hoat ssl
        $this->_active_ssl_amount = $this->_config_kernel->_cf_kernel_active_ssl;
    }

    /**
     * Default action, shows the search form
     */
    public function indexAction()
    {
    }

    public function _indexAction()
    {
        $general = new General();
        $this->directAdmin->query('/CMD_API_SUBDOMAINS', array(
            'domain' => $this->config->directAdmin->hostname,
            'action' => 'create',
            'subdomain' => 'aaa1',
        ));
        
        $result = $this->directAdmin->fetch_parsed_body();

        if (is_dir("../aaa1")) {
            $general->deleteDirectory("../aaa1");
        }

        $this->print_array($result);
        $this->view->disable();
    }

    public function deleteAllFolderAction()
    {
        $arrFolderNotdelete = ['app', 'public', 'database', 'vendor'];
        $general = new General();
        $subdomains = Subdomain::find();
        if ($subdomains->count() > 0) {
            foreach ($subdomains as $subdomain) {
                if (is_dir('../' . $subdomain->name) && !in_array($subdomain->name, $arrFolderNotdelete)) {
                    $general->deleteDirectory('../' . $subdomain->name);
                }
            }
        }
    }

    public function createAllAction()
    {
        set_time_limit(1200);
        $general = new General();
        $subdomains = Subdomain::find();
        if ($subdomains->count() > 0) {
            foreach ($subdomains as $subdomain) {
                $this->directAdmin->query('/CMD_API_SUBDOMAINS', array(
                    'domain' => $this->config->directAdmin->hostname,
                    'action' => 'create',
                    'subdomain' => $subdomain->name,
                ));

                $this->directAdmin->fetch_parsed_body();
                if (is_dir("../" . $subdomain->name)) {
                    $general->deleteDirectory("../" . $subdomain->name);
                }
            }
        }

        $this->view->disable();
    }


    /**
     * Creates a User
     *
     */
    public function createAction()
    {
        if ($this->request->isAjax()) {
            $this->view->setRenderLevel(
                View::LEVEL_ACTION_VIEW
            );
            $form = new SubdomainForm();
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

            $identity = $this->auth->getIdentity();
            $userCurrent = Users::findFirstById($identity['id']);

            if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
                $this->view->disable();
                $subdomain = new Subdomain();
                $general = new General();
                $slug = $general->create_slug($this->request->getPost('name'));
                
                $userCurrent->balance = $userCurrent->balance - $this->_money_minus_amount;
                $userCurrent->save();

                $subdomain->assign(array(
                    'create_id' => $identity['id'],
                    'name' => $this->request->getPost('name'),
                    'slug' => $slug,
                    'folder_sort' => $folder_sort,
                    'folder' => $folder,
                    'new' => 'Y',
                    'other_interface' => 'Y',
                    'active_date' => '0000-00-00 00:00:00',
                    'expired_date' => '0000-00-00 00:00:00',
                ));

                if ($subdomain->save()) {
                    
                    
                    $id = $subdomain->id;

                    // insert elastic
                    $this->elastic_service->insertSubdomain($id);

                    //save history
                    $userHistory = new UserHistory();
                    $userHistory->assign([
                        'user_id' => $identity['id'],
                        'subdomain_id' => $id,
                        'subdomain_name' => $subdomain->name,
                        'amount' => -$this->_money_minus_amount,
                        'action' => 1,
                        'summary' => 'tạo website',
                    ]);
                    $userHistory->save();

                    //add user
                    $user = new Users();
                    $user->assign([
                        'subdomain_id' => $id,
                        'profilesId' => 1,
                        'role' => 2,
                        'sort' => 1,
                        'balance' => $this->_create_website_amount,
                        'username' => $this->request->getPost('username'),
                        'password' => $this->security->hash($this->request->getPost('password'))
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
                        $result = ['code' => 0, 'id' => 0];
                    }

                    $result = ['code' => 1, 'id' => $id];
                // $this->createBannerHtml($id, $folder);

                    // return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/' . $this->_getActionName() . '?message=success');
                } else {
                    $result = ['code' => 0, 'id' => 0];
                    $this->flashSession->error($subdomain->getMessages());
                }
                
                $response = new \Phalcon\Http\Response();
                $response->setContent(json_encode($result));
                return $response;
            }
            $this->view->title_bar = 'Tạo web';
            $this->view->action = 'create';
            $this->view->form = $form;
            $this->view->userCurrent = $userCurrent;
            $this->view->money_minus = $this->_money_minus_amount;
            $this->view->min_balance = $this->_active_website_min_amount;
            $this->view->password = mt_rand(100000, 999999);
            $this->view->message = $this->request->get('message');
            $this->view->pick($this->_getControllerName() . '/form');
        }
    }

    public function createFromDomainIdAction($originId)
    {
        if ($this->request->isAjax()) {
            $subdomainOrigin = Subdomain::findFirstById($originId);
            if (!$subdomainOrigin) {
                $this->flashSession->error('Không tìm thấy dữ liệu tên miền');
                return $this->response->redirect('/' . ACP_NAME);
            }

            // get user create origin subdomain
            $userCreateOrigin = Users::findFirstById($subdomainOrigin->create_id);
            $sharePrice = $subdomainOrigin->share_price;

            $this->view->setRenderLevel(
                View::LEVEL_ACTION_VIEW
            );
            $form = new SubdomainForm();
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

            $identity = $this->auth->getIdentity();
            $userCurrent = Users::findFirstById($identity['id']);

            if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
                $this->view->disable();
                $subdomain = new Subdomain();
                $general = new General();
                $slug = $general->create_slug($this->request->getPost('name'));
                
                $userCurrent->balance = $userCurrent->balance - $this->_money_minus_amount;
                $userCurrent->save();

                $subdomain->assign(array(
                    'create_id' => $identity['id'],
                    'name' => $this->request->getPost('name'),
                    'slug' => $slug,
                    'folder_sort' => $folder_sort,
                    'folder' => $folder,
                    'new' => 'Y',
                    'other_interface' => 'Y',
                    'active_date' => '0000-00-00 00:00:00',
                    'expired_date' => '0000-00-00 00:00:00',
                ));

                if ($subdomain->save()) {
                    $id = $subdomain->id;

                    //save history
                    $userHistory = new UserHistory();
                    $userHistory->assign([
                        'user_id' => $identity['id'],
                        'subdomain_id' => $id,
                        'subdomain_name' => $subdomain->name,
                        'amount' => -$this->_money_minus_amount,
                        'action' => 1,
                        'summary' => 'tạo website',
                    ]);
                    $userHistory->save();

                    //add user
                    $user = new Users();
                    $dataUser = [
                        'subdomain_id' => $id,
                        'profilesId' => 1,
                        'role' => 2,
                        'sort' => 1,
                        'balance' => $this->_create_website_amount,
                        'username' => $this->request->getPost('username'),
                        'password' => $this->security->hash($this->request->getPost('password'))
                    ];

                    $user->assign([
                        'subdomain_id' => $id,
                        'profilesId' => 1,
                        'role' => 2,
                        'sort' => 1,
                        'balance' => $this->_create_website_amount,
                        'username' => $this->request->getPost('username'),
                        'password' => $this->security->hash($this->request->getPost('password'))
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
                    } else {
                        foreach ($user->getMessages() as $message) {
                            echo $message;
                        }
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
     

                    if ($subdomainOrigin) {
                        $this->subdomain_service->copyFolderOrigin($subdomainOrigin->folder, $folder);
                        $this->subdomain_service->createDataOrigin($subdomainOrigin, $subdomain);
                    }

                    // insert elastic
                    if (getenv('APP_ENV') == 'production') {
                        $this->elastic_service->addQueuueIndexSubdomainId($id);
                    } else {
                        $this->elastic_service->insertSubdomain($id);
                    }

                    $result = ['code' => 1, 'id' => $id];
                // $this->createBannerHtml($id, $folder);

                    // return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/' . $this->_getActionName() . '?message=success');
                } else {
                    $result = ['code' => 0, 'id' => 0];
                    $this->flashSession->error($subdomain->getMessages());
                }
                
                $response = new \Phalcon\Http\Response();
                $response->setContent(json_encode($result));
                return $response;
            }
            $this->view->title_bar = 'Tạo web mới phiên bản giống <strong><a href="//'. $subdomainOrigin->name .'.'. ROOT_DOMAIN .'" target="_blank" style="color:#f00">'. $subdomainOrigin->name .'.'. ROOT_DOMAIN .'</a></strong>';
            $this->view->action = 'createFromDomainId/' . $originId;
            $this->view->form = $form;
            $this->view->userCurrent = $userCurrent;
            $this->view->money_minus = $this->_money_minus_amount;
            $this->view->min_balance = $this->_active_website_min_amount;
            $this->view->sharePrice = $sharePrice;
            $this->view->password = mt_rand(100000, 999999);
            $this->view->message = $this->request->get('message');
            $this->view->pick($this->_getControllerName() . '/form');
        }
    }

    public function _listAction()
    {
        $identity = $this->auth->getIdentity();
        $subdomains = $this->subdomain_service->getSubdomainList();

        $breadcrumb = '<li class="active">Web đã làm</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->subdomains = $subdomains;
    }

    /**
     * Get all subdomain
     * 
     * @return View
     */
    public function allAction()
    {
        $identity = $this->auth->getIdentity();
        $numberPage = $this->request->getQuery("page", "int");
        $page_current = ($numberPage > 1) ? $numberPage : 1;
        $viewFile = 'all';
        if (empty($this->subdomainRate)) {
            $subdomains = $this->subdomain_service->getAllSubdomains($page_current);
        } else {
            $subdomainResult = $this->elastic_service->searchAllSubdomainBackend();
            $subdomainElastics = $subdomainResult['hits'];
            $subdomainArrays = [];
            foreach ($subdomainElastics as $subdomainElastic) {
                $subdomainArrays[$subdomainElastic['_id']] = $subdomainElastic['_source'];
                $subdomainArrays[$subdomainElastic['_id']]['id'] = $subdomainElastic['_id'];
            }

            $subdomainRates = [];
            foreach ($this->subdomainRate as $key => $subRate) {
                if (isset($subdomainArrays[$key])) {
                    $subdomainRates[$key] = $subdomainArrays[$key];
                    unset($subdomainArrays[$key]);
                }
            }

            $currentPage = ($this->request->getQuery('page')) ? $this->request->getQuery('page') : 1;
            $subdomains = array_merge($subdomainRates, $subdomainArrays);
            $paginator = new NativeArray(
                [
                    "data"  => $subdomains,
                    "limit" => 200,
                    "page"  => $currentPage,
                ]
            );

            $this->view->page = $paginator->getPaginate();
            $viewFile = 'all01';
        }
        
        $breadcrumb = '<li class="active">Web đã làm</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->subdomains = $subdomains;
        $this->view->page_current = $page_current;
        $this->view->pick($this->_getControllerName() . '/' . $viewFile);
    }

    /**
     * Search subdomain with elastic
     *
     * @return View
     */
    public function searchAction()
    {
        if ($this->request->isPost()) {
            if ($this->request->isAjax()) {
                $this->view->setRenderLevel(
                    View::LEVEL_ACTION_VIEW
                );
                $keyword = $this->request->getPost('keyword');
                $result = $this->elastic_service->searchSubdomain($keyword);

                $this->view->result = $result;
                $this->view->pick($this->_getControllerName() . '/searchElastic');
            }
        } else {
            $this->view->disable();
        }
    }

    public function _searchAction()
    {
        if ($this->request->isPost()) {
            if ($this->request->isAjax()) {
                $this->view->setTemplateBefore('search');
                $keyword = $this->request->getPost('keyword');
                $list = $this->subdomain_service->searchAdmin($keyword);
                
                $this->view->list = $list;
                $this->view->pick($this->_getControllerName() . '/search');
            }
        } else {
            $this->view->disable();
        }
    }

    public function fulltextAction()
    {
        $this->view->disable();
        $keyword = 'viettel';

        $test = $this->subdomain_service->fulltextSearch($keyword);
        echo '<pre>';
        print_r($test->toArray());
        echo '</pre>';
        
        
        die;
    }

    public function usingAllLayoutAction($id)
    {
        $item = Subdomain::findFirst([
            "conditions" => "id = $id AND id != ". $this->_get_subdomainID() .""
        ]);
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect('/' . ACP_NAME);
        }

        $folder = $item->folder;

        $layouts = Layout::find([
            'conditions' => 'active = "Y"'
        ]);


        foreach ($layouts as $rowLayout) {
            $layout = $rowLayout->id;
            $tmp_layout_module = TmpLayoutModule::query()
                ->columns([
                    "Modules\Models\TmpLayoutModule.id",
                    "Modules\Models\TmpLayoutModule.layout_id",
                    "Modules\Models\TmpLayoutModule.position_id",
                    "Modules\Models\TmpLayoutModule.module_item_id",
                    "Modules\Models\TmpLayoutModule.active",
                    "Modules\Models\TmpLayoutModule.sort",
                    "mi.parent_id",
                    "mi.name AS module_name",
                    "mi.id AS module_id",
                    "mi.module_group_id",
                    "mi.sort AS module_sort",
                    "mi.type",
                    "p.code as position_name",
                ])
                ->join("Modules\Models\Position", "p.id = Modules\Models\TmpLayoutModule.position_id", "p")
                ->join("Modules\Models\ModuleItem", "mi.id = Modules\Models\TmpLayoutModule.module_item_id", "mi")
                ->where("Modules\Models\TmpLayoutModule.subdomain_id = :subdomain_id:")
                ->andWhere("layout_id = :layout_id:")
                ->andWhere("mi.parent_id = :parent_id:")
                ->bind(["subdomain_id" => $id,
                    "layout_id" => $layout,
                    "parent_id" => 0
                ])
                ->orderBy("p.sort ASC, Modules\Models\TmpLayoutModule.sort ASC, mi.name ASC, Modules\Models\TmpLayoutModule.id DESC")
                ->execute();

            $tmpLayoutModuleArray = array();
            foreach ($tmp_layout_module as $key => $row) {
                $tmpLayoutModuleArray[] = [
                    'id' => $row->id,
                    'layout_id' => $row->layout_id,
                    'active' => $row->active,
                    'parent_id' => $row->parent_id,
                    'module_item_id' => $row->module_item_id,
                    'position_id' => $row->position_id,
                    'sort' => $row->sort,
                    'type' => $row->type,
                ];
            }


            $tmp_layout_module_current = TmpLayoutModule::query()
                ->columns("id")
                ->where("Modules\Models\TmpLayoutModule.subdomain_id = :subdomain_id:")
                ->andWhere("layout_id = :layout_id:")
                ->bind(["subdomain_id" => $this->_get_subdomainID(),
                    "layout_id" => $layout
                ])
                ->execute();


            if (count($tmp_layout_module_current) > 0) {
                foreach ($tmp_layout_module_current as $row) {
                    $tmp = TmpLayoutModule::findFirstById($row->id);
                    if (!empty($tmp)) {
                        $tmp->delete();
                    }
                }
            }

            foreach ($tmpLayoutModuleArray as $row) {
                $moduleItem = ModuleItem::findFirst([
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND type = '". $row["type"] ."' AND name= '". $row["module_name"] ."'"
            ]);
                if ($moduleItem) {
                    $tmp = new TmpLayoutModule();
                    $tmp->assign([
                        'layout_id' => $row['layout_id'],
                        'subdomain_id' => $this->_get_subdomainID(),
                        'position_id' => $row['position_id'],
                        'sort' => $row['sort'],
                        'active' => $row['active'],
                        'module_item_id' => $moduleItem->id,
                    ]);
                    $tmp->save();
                }
            }

            copy('assets/css/pages/'. $folder .'/style'. $layout .'.css', 'assets/css/pages/' . $this->_get_subdomainFolder() . '/style'. $layout .'.css');
            copy('assets/css/pages/'. $folder .'/page'. $layout .'.css', 'assets/css/pages/' . $this->_get_subdomainFolder() . '/page'. $layout .'.css');

            
        }

        $this->flashSession->success('Cập nhật cấu hình layout thành công');
        return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/all');
    }

    public function usingLayoutAction($id, $layout)
    {
        $item = Subdomain::findFirst([
            "conditions" => "id = $id AND id != ". $this->_get_subdomainID() .""
        ]);
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect('/' . ACP_NAME);
        }

        $layoutConfigCopy = LayoutConfig::findFirst([
            "conditions" => "subdomain_id = $id AND layout_id = $layout"
        ]);

        $layoutConfig = LayoutConfig::findFirst([
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND layout_id = $layout"
        ]);

        $layoutConfig->assign([
            'css' => $layoutConfigCopy->css,
            'enable_css' => $layoutConfigCopy->enable_css,
            'enable_color' => $layoutConfigCopy->enable_color,
            'hide_header' => $layoutConfigCopy->hide_header,
            'hide_left' => $layoutConfigCopy->hide_left,
            'hide_right' => $layoutConfigCopy->hide_right,
            'hide_footer' => $layoutConfigCopy->hide_footer,
        ]);

        $layoutConfig->save();

        //tmp_layout_module copy
        $tmp_layout_module = TmpLayoutModule::query()
            ->columns([
                "Modules\Models\TmpLayoutModule.id",
                "Modules\Models\TmpLayoutModule.layout_id",
                "Modules\Models\TmpLayoutModule.position_id",
                "Modules\Models\TmpLayoutModule.module_item_id",
                "Modules\Models\TmpLayoutModule.active",
                "Modules\Models\TmpLayoutModule.sort",
                "mi.parent_id",
                "mi.name AS module_name",
                "mi.id AS module_id",
                "mi.module_group_id",
                "mi.sort AS module_sort",
                "mi.type",
                "p.code as position_name",
            ])
            ->join("Modules\Models\Position", "p.id = Modules\Models\TmpLayoutModule.position_id", "p")
            ->join("Modules\Models\ModuleItem", "mi.id = Modules\Models\TmpLayoutModule.module_item_id", "mi")
            ->where("Modules\Models\TmpLayoutModule.subdomain_id = :subdomain_id:")
            ->andWhere("layout_id = :layout_id:")
            ->andWhere("mi.parent_id = :parent_id:")
            ->bind(["subdomain_id" => $id,
                "layout_id" => $layout,
                "parent_id" => 0
            ])
            ->orderBy("p.sort ASC, Modules\Models\TmpLayoutModule.sort ASC, mi.name ASC, Modules\Models\TmpLayoutModule.id DESC")
            ->execute();

        $tmpLayoutModuleArray = array();
        foreach ($tmp_layout_module as $key => $row) {
            $tmpLayoutModuleArray[] = [
                'id' => $row->id,
                'layout_id' => $row->layout_id,
                'active' => $row->active,
                'parent_id' => $row->parent_id,
                'module_item_id' => $row->module_item_id,
                'module_name' => $row->module_name,
                'position_id' => $row->position_id,
                'sort' => $row->sort,
                'type' => $row->type,
            ];
        }


        $tmp_layout_module_current = TmpLayoutModule::query()
            ->columns("id")
            ->where("Modules\Models\TmpLayoutModule.subdomain_id = :subdomain_id:")
            ->andWhere("layout_id = :layout_id:")
            ->bind(["subdomain_id" => $this->_get_subdomainID(),
                "layout_id" => $layout
            ])
            ->execute();


        if (count($tmp_layout_module_current) > 0) {
            foreach ($tmp_layout_module_current as $row) {
                $tmp = TmpLayoutModule::findFirstById($row->id);
                if (!empty($tmp)) {
                    $tmp->delete();
                }
            }
        }

        foreach ($tmpLayoutModuleArray as $row) {
            $moduleItem = ModuleItem::findFirst([
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND type = '". $row["type"] ."' AND name= '". $row["module_name"] ."'"
            ]);
            
            if ($moduleItem) {
                $tmp = new TmpLayoutModule();
                $tmp->assign([
                    'layout_id' => $row['layout_id'],
                    'subdomain_id' => $this->_get_subdomainID(),
                    'position_id' => $row['position_id'],
                    'sort' => $row['sort'],
                    'active' => $row['active'],
                    'module_item_id' => $moduleItem->id,
                ]);
                $tmp->save();
            }
        }

        $folder = $item->folder;
        // copy setting css
        copy('assets/css/pages/'. $folder .'/style'. $layout .'.css', 'assets/css/pages/' . $this->_get_subdomainFolder() . '/style'. $layout .'.css');
        copy('assets/css/pages/'. $folder .'/style'. $layout .'.css', 'assets/css/pages/' . $this->_get_subdomainFolder() . '/page'. $layout .'.css');

        
        
        $this->flashSession->success('Cập nhật cấu hình layout thành công');
        return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/all');
    }

    public function usingMainLayoutAction($id)
    {
        $item = Subdomain::findFirst([
            "conditions" => "id = $id AND id != ". $this->_get_subdomainID() .""
        ]);
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect('/' . ACP_NAME);
        }

        $settingCopy = Setting::findFirstBySubdomainId($id);
        $layout_id_copy = $settingCopy->layout_id;

        $layoutConfigCopy = LayoutConfig::findFirst([
            "conditions" => "subdomain_id = $id AND layout_id = $layout_id_copy"
        ]);

        $setting = Setting::findFirstBySubdomainId($this->_get_subdomainID());
        $layout = $setting->layout_id;

        $layoutConfig = LayoutConfig::findFirst([
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND layout_id = $layout"
        ]);

        $layoutConfig->assign([
            'css' => $layoutConfigCopy->css,
            'enable_css' => $layoutConfigCopy->enable_css,
            'enable_color' => $layoutConfigCopy->enable_color,
            'hide_header' => $layoutConfigCopy->hide_header,
            'hide_left' => $layoutConfigCopy->hide_left,
            'hide_right' => $layoutConfigCopy->hide_right,
            'hide_footer' => $layoutConfigCopy->hide_footer,
        ]);

        $layoutConfig->save();

        //tmp_layout_module copy
        $tmp_layout_module = TmpLayoutModule::query()
            ->columns([
                "Modules\Models\TmpLayoutModule.id",
                "Modules\Models\TmpLayoutModule.layout_id",
                "Modules\Models\TmpLayoutModule.position_id",
                "Modules\Models\TmpLayoutModule.module_item_id",
                "Modules\Models\TmpLayoutModule.active",
                "Modules\Models\TmpLayoutModule.sort",
                "mi.parent_id",
                "mi.name AS module_name",
                "mi.id AS module_id",
                "mi.module_group_id",
                "mi.sort AS module_sort",
                "mi.type",
                "p.code as position_name",
            ])
            ->join("Modules\Models\Position", "p.id = Modules\Models\TmpLayoutModule.position_id", "p")
            ->join("Modules\Models\ModuleItem", "mi.id = Modules\Models\TmpLayoutModule.module_item_id", "mi")
            ->where("Modules\Models\TmpLayoutModule.subdomain_id = :subdomain_id:")
            ->andWhere("layout_id = :layout_id:")
            ->andWhere("mi.parent_id = :parent_id:")
            ->bind(["subdomain_id" => $id,
                "layout_id" => $layout_id_copy,
                "parent_id" => 0
            ])
            ->orderBy("p.sort ASC, Modules\Models\TmpLayoutModule.sort ASC, mi.name ASC, Modules\Models\TmpLayoutModule.id DESC")
            ->execute();

        $tmpLayoutModuleArray = array();
        foreach ($tmp_layout_module as $key => $row) {
            $tmpLayoutModuleArray[] = [
                'id' => $row->id,
                'layout_id' => $row->layout_id,
                'active' => $row->active,
                'parent_id' => $row->parent_id,
                'module_item_id' => $row->module_item_id,
                'module_name' => $row->module_name,
                'position_id' => $row->position_id,
                'sort' => $row->sort,
                'type' => $row->type,
            ];
        }


        $tmp_layout_module_current = TmpLayoutModule::query()
            ->columns("id")
            ->where("Modules\Models\TmpLayoutModule.subdomain_id = :subdomain_id:")
            ->andWhere("layout_id = :layout_id:")
            ->bind(["subdomain_id" => $this->_get_subdomainID(),
                "layout_id" => $layout
            ])
            ->execute();


        if (count($tmp_layout_module_current) > 0) {
            foreach ($tmp_layout_module_current as $row) {
                $tmp = TmpLayoutModule::findFirstById($row->id);
                if (!empty($tmp)) {
                    $tmp->delete();
                }
            }
        }

        foreach ($tmpLayoutModuleArray as $row) {
            $moduleItem = ModuleItem::findFirst([
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND type = '". $row["type"] ."' AND name= '". $row["module_name"] ."'"
            ]);
            
            if ($moduleItem) {
                $tmp = new TmpLayoutModule();
                $tmp->assign([
                    'layout_id' => $layout,
                    'subdomain_id' => $this->_get_subdomainID(),
                    'position_id' => $row['position_id'],
                    'sort' => $row['sort'],
                    'active' => $row['active'],
                    'module_item_id' => $moduleItem->id,
                ]);
                $tmp->save();
            }
        }

        $folder = $item->folder;
        // copy setting css
        copy('assets/css/pages/'. $folder .'/style'. $layout_id_copy .'.css', 'assets/css/pages/' . $this->_get_subdomainFolder() . '/style'. $layout .'.css');
        copy('assets/css/pages/'. $folder .'/page'. $layout_id_copy .'.css', 'assets/css/pages/' . $this->_get_subdomainFolder() . '/page'. $layout .'.css');

        
        
        $this->flashSession->success('Cập nhật cấu hình layout thành công');
        return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/all');
    }

    public function addDomainAction($id)
    {
        $this->view->setRenderLevel(
            View::LEVEL_ACTION_VIEW
        );
        $identity = $this->auth->getIdentity();
        $item = Subdomain::findFirstById($id);
        $userCurrent = Users::findFirstById($identity['id']);

        $domains = Domain::findBySubdomainId($id);
        $balance = (count($domains) > 0) ? $userCurrent->balance - $this->_active_website_second_amount : $userCurrent->balance - $this->_active_website_amount;

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect('/' . ACP_NAME);
        }

        $form = new DomainForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            //save history
            if ($balance < $this->_active_website_min_amount) {
                $this->flashSession->error('Bạn không đủ tiền để kích hoạt website mới. Vui lòng nạp thêm tiền hoặc liên hệ nhà phát triển.');
                return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/' . $this->_getActionName() . '/' . $item->id);
            }
            $userCurrent->balance = $balance;
            $userCurrent->save();

            //save history
            $userHistory = new UserHistory();
            $amount = (count($domains) > 0) ? $this->_active_website_second_amount : $this->_active_website_amount;
            $userHistory->assign([
                'user_id' => $identity['id'],
                'subdomain_id' => $id,
                'subdomain_name' => $item->name,
                'amount' => -$amount,
                'action' => 2,
                'summary' => 'kích hoạt website',
            ]);
            $userHistory->save();

            $this->directAdmin->query('/CMD_API_DOMAIN_POINTER', array(
                'domain' => $this->config->directAdmin->hostname,
                'action' => 'add',
                'from' => $this->request->getPost('name'),
                'alias' => 'yes',
            ));

            $result = $this->directAdmin->fetch_parsed_body();

            //add domain
            $domain = new Domain();
            $domain->assign([
                "subdomain_id" => $item->id,
                "name" => $this->request->getPost("name")
            ]);
            $domain->save();
            //set active
            if (count($domains) == 0) {
                $item->assign([
                    "active" => "Y",
                    "active_date" => date("Y-m-d H:i:s", time()),
                    "expired_date" => date("Y-m-d H:i:s", strtotime("+360 days"))
                ]);
                $item->save();
            }

            $this->elastic_service->updateSubdomain($id);
            
            // die(json_encode(1));
            return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/' . $this->_getActionName() . '/' . $item->id . '?message=success&domain=' . $this->request->getPost('name'));

            
            /*if (!isset($result['error']) || $result['error'] != 0) {
                return $this->flashSession->error("Đã có lỗi xảy ra với tên miền " . $this->request->getPost('name') ." . Vui lòng liên hệ hỗ trợ để được trợ giúp");
            }*/
        }
        

        $this->view->title_bar = 'Thêm tên miền cho subdomain <span class="text-primary">' . $item->name . '.' . ROOT_DOMAIN . '</span>';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->item = $item;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->ipAdress = $this->request->getServerAddress();
        $this->view->count_domain = count($domains);
        $this->view->userCurrent = $userCurrent;
        $this->view->money_minus = (count($domains) > 0) ? $this->_active_website_second_amount : $this->_active_website_amount;
        $this->view->min_balance = $this->_active_website_min_amount;
        $this->view->form = $form;
        $this->view->message = $this->request->get('message');
        $this->view->domain = $this->request->get('domain');
        $this->view->pick($this->_getControllerName() . '/add_domain');
    }

    /**
     * Renewed day expired subdomain
     * 
     * @param integer $id
     */
    public function addExpiredDateAction($id)
    {
        $this->view->setTemplateBefore('add_expired');
        $this->view->setRenderLevel(
            View::LEVEL_ACTION_VIEW
        );
        $identity = $this->auth->getIdentity();
        $item = Subdomain::findFirst([
            "conditions" => "id = $id AND active= 'Y'"
        ]);
        $userCurrent = Users::findFirstById($identity['id']);
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect('/' . ACP_NAME);
        }
        if ($this->request->isPost()) {
            $this->view->disable();
            //save history
            $balance = $userCurrent->balance - $this->_renewal_website_amount * $this->request->getPost('year');
            if ($balance < $this->_active_website_min_amount) {
                $result = 0;
                $this->flashSession->error('Bạn không đủ tiền để gia hạn website. Vui lòng nạp thêm tiền hoặc liên hệ nhà phát triển.');
                return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/' . $this->_getActionName() . '/' . $item->id);
            }

            $userCurrent->balance = $balance;
            $userCurrent->save();

            //save history
            $userHistory = new UserHistory();
            $userHistory->assign([
                'user_id' => $identity['id'],
                'subdomain_id' => $id,
                'subdomain_name' => $item->name,
                'amount' => -$this->_renewal_website_amount,
                'action' => 3,
                'summary' => 'gia hạn website',
            ]);
            $userHistory->save();
            $day = $this->request->getPost('year') * 360;

            // check if current date larger expired date => add current date + 365 days; else add  expired date + 365 days
            $expiredRenew = (date('Y-m-d') > date('Y-m-d', strtotime($item->expired_date))) ? date("Y-m-d H:i:s", strtotime("+$day days")) : date("Y-m-d H:i:s", strtotime("+$day days", strtotime($item->expired_date)));
            $item->assign([
                "expired_date" => $expiredRenew
            ]);
            if ($item->save()) {
                $result = 1;
                $this->flashSession->success("Gia hạn website ". $item->name . "." . $this->mainGlobal->getRootDomain() ." thành công");
            } else {
                $result = '';
                foreach ($item->getMessages() as $message) {
                    $result .= $message;
                }
            }

            // update elastic and redis
            $this->elasticRedis($id);

            $response = new Response();
            $response->setContent(json_encode($result));

            return $response;
        }

        $this->view->item = $item;
        $this->view->userCurrent = $userCurrent;
        $this->view->money_minus = $this->_renewal_website_amount;
        $this->view->min_balance = $this->_active_website_min_amount;
        $this->view->title_bar = "Gia hạn website ". $item->name ."";
        $this->view->pick($this->_getControllerName() . '/add_expired');
    }

    public function activeAction($id)
    {
        $identity = $this->auth->getIdentity();

        if ($identity['role'] == 1) {
            $conditions = "id = $id";
        } else {
            $conditions = "id = $id AND create_id = ". $identity['id'] ."";
        }

        $item = Subdomain::findFirst([
            "conditions" => $conditions
        ]);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        $item->assign([
            "active" => "Y",
            "active_date" => date("Y-m-d H:i:s", time()),
            "expired_date" => date("Y-m-d H:i:s", strtotime("+360 days"))
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            
            $this->flashSession->success("Kích hoạt website thành công");
            return $this->response->redirect(ACP_NAME);
        }
    }

    public function suspendedAction($id)
    {
        $identity = $this->auth->getIdentity();
        $item = Subdomain::findFirstById($id);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        $listUserNameManage = TmpSubdomainUser::query()
                    ->columns("s.name")
                    ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                    ->where("u.subdomain_id = :subdomain_id:")
                    ->bind(["subdomain_id" => $id])
                    ->execute();

        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }

        if ($identity['role'] != 1) {
            if (!empty($arrayUserNameManage)) {
                if (!in_array($identity['subdomain_name'], $arrayUserNameManage)) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            } else {
                if ($item->create_id != $identity['id']) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            }
        }

        $item->assign([
            "suspended" => "Y"
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            
            $this->flashSession->success("Khóa website thành công");
            return $this->response->redirect(ACP_NAME);
        }
    }

    public function closedAction($id)
    {
        $identity = $this->auth->getIdentity();
        $item = Subdomain::findFirstById($id);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        $listUserNameManage = TmpSubdomainUser::query()
                    ->columns("s.name")
                    ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                    ->where("u.subdomain_id = :subdomain_id:")
                    ->bind(["subdomain_id" => $id])
                    ->execute();

        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }

        if ($identity['role'] != 1) {
            if (!empty($arrayUserNameManage)) {
                if (!in_array($identity['subdomain_name'], $arrayUserNameManage)) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            } else {
                if ($item->create_id != $identity['id']) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            }
        }
        
        $item->assign([
            "closed" => "Y"
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            
            $this->flashSession->success("Đóng website thành công");
            return $this->response->redirect(ACP_NAME);
        }
    }

    public function unClosedAction($id)
    {
        $identity = $this->auth->getIdentity();
        $item = Subdomain::findFirstById($id);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        $listUserNameManage = TmpSubdomainUser::query()
                    ->columns("s.name")
                    ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                    ->where("u.subdomain_id = :subdomain_id:")
                    ->bind(["subdomain_id" => $id])
                    ->execute();

        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }

        if ($identity['role'] != 1) {
            if (!empty($arrayUserNameManage)) {
                if (!in_array($identity['subdomain_name'], $arrayUserNameManage)) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            } else {
                if ($item->create_id != $identity['id']) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            }
        }

        $item->assign([
            "closed" => "N"
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            $this->flashSession->success("Mở website thành công");
            
            return $this->response->redirect(ACP_NAME);
        }
    }

    public function unSuspendedAction($id)
    {
        $identity = $this->auth->getIdentity();
        $item = Subdomain::findFirstById($id);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        $listUserNameManage = TmpSubdomainUser::query()
                    ->columns("s.name")
                    ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                    ->where("u.subdomain_id = :subdomain_id:")
                    ->bind(["subdomain_id" => $id])
                    ->execute();

        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }

        if ($identity['role'] != 1) {
            if (!empty($arrayUserNameManage)) {
                if (!in_array($identity['subdomain_name'], $arrayUserNameManage)) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            } else {
                if ($item->create_id != $identity['id']) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            }
        }

        $item->assign([
            "suspended" => "N"
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            
            $this->flashSession->success("Hủy khóa website thành công");
            return $this->response->redirect(ACP_NAME);
        }
    }

    public function displayAction($id)
    {
        $identity = $this->auth->getIdentity();
        
        /*if ($identity['role'] == 1) {
            $conditions = "id = $id";
        } else {
            $conditions = "id = $id AND create_id = ". $identity['id'] ."";
        }*/

        $item = Subdomain::findFirstById($id);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        $listUserNameManage = TmpSubdomainUser::query()
                    ->columns("s.name")
                    ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                    ->where("u.subdomain_id = :subdomain_id:")
                    ->bind(["subdomain_id" => $id])
                    ->execute();

        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }

        if ($identity['role'] != 1) {
            if (!empty($arrayUserNameManage)) {
                if (!in_array($identity['subdomain_name'], $arrayUserNameManage)) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            } else {
                if ($item->create_id != $identity['id']) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            }
        }

        $item->assign([
            "display" => "Y"
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            
            $this->flashSession->success("Cập nhật dữ liệu thành công");
            return $this->response->redirect(ACP_NAME);
        }
    }

    public function unDisplayAction($id)
    {
        $identity = $this->auth->getIdentity();
        $conditions = "id = $id";

        $listUserNameManage = TmpSubdomainUser::query()
                    ->columns("s.name")
                    ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                    ->where("u.subdomain_id = :subdomain_id:")
                    ->bind(["subdomain_id" => $id])
                    ->execute();

        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }

        $item = Subdomain::findFirstById($id);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        if ($identity['role'] != 1) {
            if (!empty($arrayUserNameManage)) {
                if (!in_array($identity['subdomain_name'], $arrayUserNameManage)) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            } else {
                if ($item->create_id != $identity['id']) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            }
        }

        $item->assign([
            "display" => "N"
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            
            $this->flashSession->success("Cập nhật dữ liệu thành công");
            return $this->response->redirect(ACP_NAME);
        }
    }

    public function showDuplicateAction($id)
    {
        $identity = $this->auth->getIdentity();
        $conditions = "id = $id";

        $listUserNameManage = TmpSubdomainUser::query()
                    ->columns("s.name")
                    ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                    ->where("u.subdomain_id = :subdomain_id:")
                    ->bind(["subdomain_id" => $id])
                    ->execute();

        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }

        $item = Subdomain::findFirstById($id);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        if ($identity['role'] != 1) {
            if (!empty($arrayUserNameManage)) {
                if (!in_array($identity['subdomain_name'], $arrayUserNameManage)) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            } else {
                if ($item->create_id != $identity['id']) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            }
        }

        $item->assign([
            "duplicate" => "Y"
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            
            $this->flashSession->success("Cập nhật dữ liệu thành công");
            return $this->response->redirect(ACP_NAME);
        }
    }

    public function unDuplicateAction($id)
    {
        $identity = $this->auth->getIdentity();
        $conditions = "id = $id";

        $listUserNameManage = TmpSubdomainUser::query()
                    ->columns("s.name")
                    ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                    ->where("u.subdomain_id = :subdomain_id:")
                    ->bind(["subdomain_id" => $id])
                    ->execute();

        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }

        $item = Subdomain::findFirstById($id);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        if ($identity['role'] != 1) {
            if (!empty($arrayUserNameManage)) {
                if (!in_array($identity['subdomain_name'], $arrayUserNameManage)) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            } else {
                if ($item->create_id != $identity['id']) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            }
        }

        $item->assign([
            "duplicate" => "N"
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            
            $this->flashSession->success("Cập nhật dữ liệu thành công");
            return $this->response->redirect(ACP_NAME);
        }
    }

    public function copyRightAction($id)
    {
        $identity = $this->auth->getIdentity();

        $item = Subdomain::findFirstById($id);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        $listUserNameManage = TmpSubdomainUser::query()
                    ->columns("s.name")
                    ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                    ->where("u.subdomain_id = :subdomain_id:")
                    ->bind(["subdomain_id" => $id])
                    ->execute();

        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }

        if ($identity['role'] != 1) {
            if (!empty($arrayUserNameManage)) {
                if (!in_array($identity['subdomain_name'], $arrayUserNameManage)) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            } else {
                if ($item->create_id != $identity['id']) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            }
        }

        $item->assign([
            "copyright" => "Y"
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            
            $this->flashSession->success("Cập nhật dữ liệu thành công");
            return $this->response->redirect(ACP_NAME);
        }
    }

    public function unCopyrightAction($id)
    {
        $identity = $this->auth->getIdentity();

        $conditions = "id = $id";

        $listUserNameManage = TmpSubdomainUser::query()
                    ->columns("s.name")
                    ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                    ->where("u.subdomain_id = :subdomain_id:")
                    ->bind(["subdomain_id" => $id])
                    ->execute();

        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }


        $item = Subdomain::findFirstById($id);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        if ($identity['role'] != 1) {
            if (!empty($arrayUserNameManage)) {
                if (!in_array($identity['subdomain_name'], $arrayUserNameManage)) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            } else {
                if ($item->create_id != $identity['id']) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            }
        }

        $item->assign([
            "copyright" => "N"
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            
            $this->flashSession->success("Cập nhật dữ liệu thành công");
            return $this->response->redirect(ACP_NAME);
        }
    }

    public function otherInterfaceAction($id)
    {
        $identity = $this->auth->getIdentity();

        $item = Subdomain::findFirstById($id);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        $listUserNameManage = TmpSubdomainUser::query()
                    ->columns("s.name")
                    ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                    ->where("u.subdomain_id = :subdomain_id:")
                    ->bind(["subdomain_id" => $id])
                    ->execute();

        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }

        if ($identity['role'] != 1) {
            if (!empty($arrayUserNameManage)) {
                if (!in_array($identity['subdomain_name'], $arrayUserNameManage)) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            } else {
                if ($item->create_id != $identity['id']) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            }
        }

        $item->assign([
            "other_interface" => "Y"
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            
            $this->flashSession->success("Cập nhật dữ liệu thành công");
            return $this->response->redirect(ACP_NAME);
        }
    }

    public function unOtherInterfaceAction($id)
    {
        $identity = $this->auth->getIdentity();

        $conditions = "id = $id";

        $listUserNameManage = TmpSubdomainUser::query()
                    ->columns("s.name")
                    ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                    ->where("u.subdomain_id = :subdomain_id:")
                    ->bind(["subdomain_id" => $id])
                    ->execute();

        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }

        $item = Subdomain::findFirstById($id);

        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            return $this->response->redirect('/' . ACP_NAME);
        }

        if ($identity['role'] != 1) {
            if (!empty($arrayUserNameManage)) {
                if (!in_array($identity['subdomain_name'], $arrayUserNameManage)) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            } else {
                if ($item->create_id != $identity['id']) {
                    $this->flashSession->error("Bạn không có quyền thực hiện hành động này");
                    return $this->response->redirect('/' . ACP_NAME);
                }
            }
        }

        $item->assign([
            "other_interface" => "N"
        ]);

        if ($item->save()) {
            // update elastic and redis
            $this->elasticRedis($id);
            
            $this->flashSession->success("Cập nhật dữ liệu thành công");
            return $this->response->redirect(ACP_NAME);
        }
    }

    /**
     * active ssl subdomain
     *
     * @param int $id
     * @return Phalcon response
     */
    public function isSslAction($id)
    {
        $identity = $this->auth->getIdentity();
        $item = Subdomain::findFirstById($id);
        if ($item && $item->is_ssl == 'N') {
            $domains = Domain::findBySubdomainId($id);
            if (count($domains) > 0) {
                $userCurrent = Users::findFirstById($identity['id']);
                if (($userCurrent->balance - $this->_active_ssl_amount) < $this->_active_website_min_amount) {
                    $this->flashSession->error('Bạn không đủ tiền để kích hoạt ssl. Vui lòng nạp thêm tiền hoặc liên hệ nhà phát triển.');
                    return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/' . $this->_getActionName() . '/' . $item->id);
                }

                // minus ssl
                $userCurrent->balance = $userCurrent->balance - $this->_active_ssl_amount;
                $userCurrent->save();

                // active ssl
                $item->is_ssl = 'Y';
                $item->save();

                $mail = new MyPHPMailer();
                $params = [
                    'name' => 'yêu cầu kích hoạt ssl mới từ domain ' . $item->name . '.' . ROOT_DOMAIN,
                    'url' => 'http://1.110.vn:2222/'
                ];
                $mail->send('congngotn@gmail.com', 'Thông tin SSL', 'Yêu cầu kích hoạt SSL mới từ 110.vn', $params);

                $this->flashSession->success("SSL sẽ bắt dầu hoạt động vào 7h sáng hôm sau.");
            } else {
                $this->flashSession->error("Tên miền chưa được kích hoạt.");
            }

            return $this->response->redirect(ACP_NAME);
        }
    }

    public function deleteAction($id)
    {
        $identity = $this->auth->getIdentity();

        if ($identity['role'] == 1) {
            $item = Subdomain::findFirst([
                "conditions" => "id = $id"
            ]);

            if (!$item) {
                $this->flashSession->error("Không tìm thấy dữ liệu");
                return $this->response->redirect('/' . ACP_NAME);
            }

            if ($item->name != '@') {
                $tmpSubdomainUsers = TmpSubdomainUser::findBySubdomainId($id);
                if ($tmpSubdomainUsers->count() > 0) {
                    foreach ($tmpSubdomainUsers as $tmpSubdomainUser) {
                        $userSub = Users::findFirstById($tmpSubdomainUser->user_id);
                    }
                }

                $tmpSubdomainUsers = TmpSubdomainUser::query()
                ->columns(["s.id AS sub_id"])
                ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                ->where("u.subdomain_id = :subdomain_id:")
                ->bind(["subdomain_id" => $id])
                ->execute();
                if ($tmpSubdomainUsers->count() > 0) {
                    foreach ($tmpSubdomainUsers as $tmpSubdomainUser) {
                    }
                }

                TmpSubdomainUser::deleteByRawSql('subdomain_id ='. $id .'');
                $user = Users::findFirstBySubdomainId($id);
                if ($user) {
                    $userHistories = UserHistory::findByUserId($user->id);
                    if (count($userHistories) > 0) {
                        foreach ($userHistories as $userHistory) {
                            $userHistory->delete();
                        }
                    }

                    $userHistoryTransfers = UserHistoryTransfer::findByUserId($user->id);
                    if (count($userHistoryTransfers) > 0) {
                        foreach ($userHistoryTransfers as $userHistoryTransfer) {
                            $userHistoryTransfer->delete();
                        }
                    }

                    $user->delete();
                }

                //delete domain
                $domains = Domain::findBySubdomainId($id);
                if (count($domains) > 0) {
                    $directAdmin = [
                        'domain' => $this->config->directAdmin->oldhostname,
                        'action' => 'delete',
                    ];

                    $i = 0;
                    foreach ($domains as $row) {
                        $domain = Domain::findFirstById($row->id);
                        $directAdmin['select' . $i] = $domain->name;
                        $domain->delete();
                        $i++;
                    }

                    $this->directAdmin->query('/CMD_API_DOMAIN_POINTER', $directAdmin);
                    $result = $this->directAdmin->fetch_parsed_body();
                    if (!isset($result['error']) || $result['error'] != 0) {
                        $directAdmin = [
                            'domain' => $this->config->directAdmin->hostname,
                            'action' => 'delete',
                        ];
                        $i = 0;
                        foreach ($domains as $row) {
                            $domain = Domain::findFirstById($row->id);
                            $directAdmin['select' . $i] = $domain->name;
                            $domain->delete();
                            $i++;
                        }

                        $this->directAdmin->query('/CMD_API_DOMAIN_POINTER', $directAdmin);
                        $this->directAdmin->fetch_parsed_body();
                    }
                }

                //delete Setting
                $setting = Setting::findFirstBySubdomainId($id);
                if ($setting) {
                    $setting->delete();
                }

                //delete module item
                $moduleItems = ModuleItem::findBySubdomainId($id);
                if (count($moduleItems) > 0) {
                    foreach ($moduleItems as $moduleItem) {
                        $moduleItem->delete();
                    }
                }

                //delete layout config
                $layoutConfigs = LayoutConfig::findBySubdomainId($id);
                if (count($layoutConfigs) > 0) {
                    foreach ($layoutConfigs as $layoutConfig) {
                        $layoutConfig->delete();
                    }
                }

                //delete Menu
                $menus = Menu::findBySubdomainId($id);
                if (count($menus) > 0) {
                    foreach ($menus as $menu) {
                        $menuItems = MenuItem::findByMenuId($menu->id);
                        if (count($menuItems) > 0) {
                            foreach ($menuItems as $rowMenu) {
                                $menuItem = MenuItem::findFirstById($rowMenu->id);
                                $menuItem->delete();
                            }
                        }
                        
                        $menu->delete();
                    }
                }

                //delete NewsType
                $newsTypes = NewsType::findBySubdomainId($id);
                if (count($newsTypes) > 0) {
                    foreach ($newsTypes as $newsType) {
                        $newsCategories = NewsCategory::findByTypeId($newsType->id);
                        if (count($newsCategories) > 0) {
                            foreach ($newsCategories as $rowNewsCategory) {
                                $newsCategory = NewsCategory::findFirstById($rowNewsCategory->id);
                                $newsCategory->delete();
                            }
                        }

                        $newss = News::findByTypeId($newsType->id);
                        if (count($newss) > 0) {
                            foreach ($newss as $rowNews) {
                                $news = News::findFirstById($rowNews->id);
                                $news->delete();
                            }
                        }
                        
                        $newsType->delete();
                    }
                }

                //delete NewsMenu
                $newsMenus = NewsMenu::findBySubdomainId($id);
                if (count($newsMenus) > 0) {
                    foreach ($newsMenus as $newsMenu) {
                        $newsMenu->delete();
                    }
                }

                //delete News
                $news = News::findBySubdomainId($id);
                if (count($news) > 0) {
                    foreach ($news as $n) {
                        $n->delete();
                    }
                }

                //delete product
                $products = Product::findBySubdomainId($id);
                if (count($products) > 0) {
                    foreach ($products as $product) {
                        $productContents = ProductContent::findByProductId($product->id);
                        if (count($productContents) > 0) {
                            foreach ($productContents as $productContent) {
                                $productContent->delete();
                            }
                        }
                        $product->delete();
                    }
                }

                //delete product detail
                $productDetails = ProductDetail::findBySubdomainId($id);
                if (count($productDetails) > 0) {
                    foreach ($productDetails as $productDetail) {
                        $productDetail->delete();
                    }
                }

                //delete product element
                $productElements = ProductElement::findBySubdomainId($id);
                if (count($productElements) > 0) {
                    foreach ($productElements as $row) {
                        $productElementDetails = ProductElementDetail::findByProductElementId($row->id);
                        if (count($productElementDetails) > 0) {
                            foreach ($productElementDetails as $productElementDetail) {
                                $productElementDetail->delete();
                            }
                        }
                        $productElement = ProductElement::findFirstById($row->id);
                        $productElement->delete();
                    }
                }

                //delete product category
                $categories = Category::findBySubdomainId($id);
                if (count($categories) > 0) {
                    foreach ($categories as $category) {
                        $category->delete();
                    }
                }

                //delete config item
                $configItems = ConfigItem::findBySubdomainId($id);
                if (count($configItems) > 0) {
                    foreach ($configItems as $configItem) {
                        $configItem->delete();
                    }
                }

                $wordItems = WordItem::findBySubdomainId($id);
                if (count($wordItems) > 0) {
                    foreach ($wordItems as $wordItem) {
                        $wordItem->delete();
                    }
                }

                //delete BannerType
                $bannerTypes = BannerType::findBySubdomainId($id);
                if (count($bannerTypes) > 0) {
                    foreach ($bannerTypes as $bannerType) {
                        $bannerType->delete();
                    }
                }

                //delete Banner
                $banners = Banner::findBySubdomainId($id);
                if (count($banners) > 0) {
                    foreach ($banners as $banner) {
                        $banner->delete();
                    }
                }

                //delete posts
                $posts = Posts::findBySubdomainId($id);
                if (count($posts) > 0) {
                    foreach ($posts as $post) {
                        $post->delete();
                    }
                }

                //delete contact
                $contacts = Contact::findBySubdomainId($id);
                if (count($contacts) > 0) {
                    foreach ($contacts as $contact) {
                        $contact->delete();
                    }
                }

                //delete Background
                $backgrounds = Background::findBySubdomainId($id);
                if (count($backgrounds) > 0) {
                    foreach ($backgrounds as $background) {
                        $background->delete();
                    }
                }

                //delete Clip
                $clips = Clip::findBySubdomainId($id);
                if (count($clips) > 0) {
                    foreach ($clips as $clip) {
                        $clip->delete();
                    }
                }

                //delete landding page
                $landindPages = LandingPage::findBySubdomainId($id);
                if (count($landindPages) > 0) {
                    foreach ($landindPages as $landindPage) {
                        $landindPages->delete();
                    }
                }

                //delete subdomain rating
                $subdomainRates = SubdomainRating::findBySubdomainId($id);
                if (count($subdomainRates) > 0) {
                    foreach ($subdomainRates as $subdomainRate) {
                        $subdomainRate->delete();
                    }
                }

                TmpPositionModuleItem::deleteByRawSql('subdomain_id ='. $id .'');
                TmpLayoutModule::deleteByRawSql('subdomain_id ='. $id .'');
                TmpNewsNewsCategory::deleteByRawSql('subdomain_id ='. $id .'');
                TmpNewsNewsMenu::deleteByRawSql('subdomain_id ='. $id .'');
                TmpProductCategory::deleteByRawSql('subdomain_id ='. $id .'');
                TmpProductProductElementDetail::deleteByRawSql('subdomain_id ='. $id .'');
                TmpBannerBannerType::deleteByRawSql('subdomain_id ='. $id .'');
                TmpProductFormItem::deleteByRawSql('subdomain_id ='. $id .'');
                TmpSubdomainLanguage::deleteByRawSql('subdomain_id ='. $id .'');
                TmpLandingModule::deleteByRawSql('subdomain_id ='. $id .'');

                //delete folder
                $folder = $item->folder;
                $subdomainName = $item->name;
                $general = new General();

                if (is_dir("uploads/" . $folder)) {
                    $general->deleteDirectory("uploads/" . $folder);
                }
                
                if (is_dir("files/" . $folder)) {
                    $general->deleteDirectory("files/" . $folder);
                }

                if (is_dir("files/default/" . $folder)) {
                    $general->deleteDirectory("files/default/" . $folder);
                }

                if (is_dir("files/ads/" . $folder)) {
                    $general->deleteDirectory("files/ads/" . $folder);
                }

                if (is_dir("files/news/" . $folder)) {
                    $general->deleteDirectory("files/news/" . $folder);
                }

                if (is_dir("files/youtube/" . $folder)) {
                    $general->deleteDirectory("files/youtube/" . $folder);
                }

                if (is_dir("files/product/" . $folder)) {
                    $general->deleteDirectory("files/product/" . $folder);
                }

                if (is_dir("files/category/" . $folder)) {
                    $general->deleteDirectory("files/category/" . $folder);
                }

                if (is_dir("assets/css/pages/" . $folder)) {
                    $general->deleteDirectory("assets/css/pages/" . $folder);
                }

                if (is_dir("bannerhtml/" . $folder)) {
                    $general->deleteDirectory("bannerhtml/" . $folder);
                }

                if (is_dir("message/subdomains/" . $folder)) {
                    $general->deleteDirectory("message/subdomains/" . $folder);
                }

                if (file_exists('counter/counter-' . $id . '.txt')) {
                    @unlink('counter/counter-' . $id . '.txt');
                }

                if (file_exists('counter_ip/counter-ip-' . $id . '.txt')) {
                    @unlink('counter_ip/counter-ip-' . $id . '.txt');
                }

                if ($item->delete()) {
                    // delete elastic subdomain
                    $this->elastic_service->deleteSubdomain($id);
                    // delete redis subdomain child
                    $this->redis_service->_deleteHasKey('subdomain', ['subdomain_id' => $id]);
                    
                }
                $this->flashSession->success("Xóa tên miền ". $item->name ." thành công");
                $this->response->redirect('/' . ACP_NAME);
            }
        }
    }

    public function changeDateExpiredAction()
    {
        if ($this->request->getPost() != '') {
            $identity = $this->auth->getIdentity();
            $date = $this->request->getPost('date');
            $date = str_replace('/', '-', $date);
            $date = date('Y-m-d', strtotime($date));
            $id = $this->request->getPost('id');
            $subdomain = Subdomain::findFirstById($id);
            if ($subdomain) {
                $subdomain->expired_date = $date;
                $subdomain->save();
                // update elastic
                $this->elasticRedis($subdomain->id);
                echo 1;
            } else {
                echo 0;
            }
        }
        $this->view->disable();
    }

    public function saveNoteAction()
    {
        if ($this->request->getPost() != '') {
            $notes = $this->request->getPost('note');
            if (!empty($notes)) {
                $identity = $this->auth->getIdentity();
                foreach ($notes as $note) {
                    $subdomain = Subdomain::findFirstById($note['id']);
                    if ($subdomain) {
                        $subdomain->note = $note['value'];
                        $subdomain->save();
                       // update elastic
                        $this->elasticRedis($subdomain->id);
                    }
                }
                
                
                echo 1;
            } else {
                echo 0;
            }
        }
        $this->view->disable();
    }
    
    public function addUserAction()
    {
        if ($this->request->isPost()) {
            if ($this->request->isAjax()) {
                $this->view->setRenderLevel(
                    View::LEVEL_ACTION_VIEW
                );
                $id = $this->request->getPost('id');
                $subdomain = Subdomain::findFirstById($id);
                $tmp = TmpSubdomainUser::findBySubdomainId($id);
                $tmpSelected = [];
                if (count($tmp) > 0) {
                    foreach ($tmp as $row) {
                        $tmpSelected[] = $row->user_id;
                    }
                }
                $user = Users::findFirstBySubdomainId($id);
                $users = Users::query()
                    ->columns(["Modules\Models\Users.id","Modules\Models\Users.username","s.name"])
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\Users.subdomain_id", "s")
                    ->where("Modules\Models\Users.role != :role:")
                    ->andWhere("Modules\Models\Users.id != :user_id:")
                    ->andWhere("s.create_id != :create_id:")
                    ->bind(["role" => 1, "user_id" => $user->id, "create_id" => $user->id])
                    ->orderBy("s.name ASC")
                    ->execute();
                $this->view->tmp_selected = $tmpSelected;
                $this->view->subdomain = $subdomain;
                $this->view->users = $users;
                $this->view->pick($this->_getControllerName() . '/add_user');
            }
        }
    }

    public function saveUserAction($id)
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $username = $this->request->getPost('username');
            TmpSubdomainUser::deleteByRawSql('subdomain_id ='. $id .'');
            if (!empty($username)) {
                $identity = $this->auth->getIdentity();
                foreach ($username as $row) {
                    $tmpSubdomainUser = new TmpSubdomainUser();
                    $tmpSubdomainUser->subdomain_id = $id;
                    $tmpSubdomainUser->user_id = $row;
                    $tmpSubdomainUser->save();
                }
                
                echo 1;
            } else {
                echo 0;
            }
        }
        $this->view->disable();
    }

    public function addUserManageAction()
    {
        if ($this->request->isPost()) {
            if ($this->request->isAjax()) {
                $this->view->setRenderLevel(
                    View::LEVEL_ACTION_VIEW
                );

                $id = $this->request->getPost('id');
                $subdomain = Subdomain::findFirstById($id);

                $tmp = TmpSubdomainUser::query()
                ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                ->where("u.subdomain_id = :subdomain_id:")
                ->bind(["subdomain_id" => $id])
                ->execute();

                $tmpSelected = [];
                if (count($tmp) > 0) {
                    foreach ($tmp as $row) {
                        $tmpSelected[] = $row->subdomain_id;
                    }
                }

                $user = Users::findFirstBySubdomainId($id);
                $users = Users::query()
                    ->columns(["s.id","Modules\Models\Users.username","s.name"])
                    ->join("Modules\Models\Subdomain", "s.id = Modules\Models\Users.subdomain_id", "s")
                    ->where("Modules\Models\Users.role != :role:")
                    ->andWhere("Modules\Models\Users.id != :user_id:")
                    ->andWhere("s.create_id != :create_id:")
                    ->bind(["role" => 1, "user_id" => $user->id, "create_id" => $user->id])
                    ->orderBy("s.name ASC")
                    ->execute();

                $this->view->tmp_selected = $tmpSelected;
                $this->view->subdomain = $subdomain;
                $this->view->users = $users;
                $this->view->pick($this->_getControllerName() . '/add_user');
            }
        }
    }

    public function saveUserManageAction($id)
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $user = Users::findFirstBySubdomainId($id);
            $userId = $user->id;
            $subdomains = $this->request->getPost('username');
            TmpSubdomainUser::deleteByRawSql('user_id ='. $userId .'');
            if (!empty($subdomains)) {
                $identity = $this->auth->getIdentity();
                foreach ($subdomains as $row) {
                    $tmpSubdomainUser = new TmpSubdomainUser();
                    $tmpSubdomainUser->subdomain_id = $row;
                    $tmpSubdomainUser->user_id = $userId;
                    $tmpSubdomainUser->save();
                }
                
                echo 1;
            } else {
                echo 0;
            }
        }
        $this->view->disable();
    }

    public function updateCopyrightAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $identity = $this->auth->getIdentity();
            $id = $this->request->getPost('id');
            $type = $this->request->getPost('type');
            $value = $this->request->getPost('value');
            $subdomain = Subdomain::findFirstById($id);
            if ($subdomain) {
                switch ($type) {
                    case 'copyright_link':
                        $subdomain->copyright_link = $value;
                        break;

                    case 'copyright_name':
                        $subdomain->copyright_name = $value;
                        break;
                }

                if ($subdomain->save()) {
                    // update elastic
                    $this->elastic_service->updateSubdomain($id);
                    
                    // delete redis subdomain child
                    $this->redis_service->_deleteHasKey('subdomain', ['subdomain_id' => $id]);
                    
                    $result = 1;
                } else {
                    $result = 0;
                }

                $response = new Response();
                $response->setContent(json_encode($result));

                return $response;
            }
        }

        $this->view->disable();
    }

    /**
     *
     * update rating for subdomain
     * return json resonse
     */
    public function updateRatingAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $userId = $this->request->getPost('user_id');
            $subdomainId = $this->request->getPost('subdomain_id');
            $rate = $this->request->getPost('rate');

            $subdomainRating = SubdomainRating::findFirst([
                "conditions" => "user_id = $userId AND subdomain_id = $subdomainId"
            ]);

            if (!$subdomainRating) {
                $subdomainRating = new SubdomainRating();
                $subdomainRating->user_id = $userId;
                $subdomainRating->subdomain_id = $subdomainId;
            }

            $response = new Response();
            $subdomainRating->rate = $rate;
            if ($subdomainRating->save()) {
                $result = [
                    'code' => 200,
                    'messages' => 'Success'
                ];

                // update elastic
                $this->elastic_service->updateSubdomain($subdomainId);
                
                // delete redis subdomain child
                $this->redis_service->_deleteHasKey('subdomain', ['subdomain_id' => $subdomainId]);
            } else {
                $result = [
                    'code' => 500,
                    'messages' => 'Error'
                ];
            }

            $response->setContent(json_encode($result));

            return $response;
        }

        $this->view->disable();
    }

    // tach source
    public function detachmentAction($id)
    {
        $identity = $this->auth->getIdentity();
        if ($identity['role'] != 1) {
            $this->dispatcher->forward(array(
                'module' => 'backend',
                'controller' => 'index',
                'action' => 'noPermission'
            ));
            return false;
        }

        $subdomain = Subdomain::findFirstById($id);
        if (!$subdomain) {
            $this->flashSession->error('Không tìm thấy tên miền');
            return $this->response->redirect(ACP_NAME);
        }

        $folder = $subdomain->folder;

        if (!is_dir("storage")) {
            mkdir("storage", 0777);
        }

        if (!is_dir("storage/" . $folder)) {
            mkdir("storage/" . $folder, 0777);
        }

        if (!is_dir("storage/" . $folder . "/public")) {
            mkdir("storage/" . $folder . "/public", 0777);
        }

        if (!is_dir("storage/" . $folder . "/database")) {
            mkdir("storage/" . $folder . "/database", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/assets")) {
            mkdir("storage/" . $folder . "/public/assets", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/assets/css")) {
            mkdir("storage/" . $folder . "/public/assets/css", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/assets/css/pages")) {
            mkdir("storage/" . $folder . "/public/assets/css/pages", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/bannerhtml")) {
            mkdir("storage/" . $folder . "/public/bannerhtml", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/uploads")) {
            mkdir("storage/" . $folder . "/public/uploads", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/files")) {
            mkdir("storage/" . $folder . "/public/files", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/files/ads")) {
            mkdir("storage/" . $folder . "/public/files/ads", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/files/category")) {
            mkdir("storage/" . $folder . "/public/files/category", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/files/default")) {
            mkdir("storage/" . $folder . "/public/files/default", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/files/news")) {
            mkdir("storage/" . $folder . "/public/files/news", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/files/product")) {
            mkdir("storage/" . $folder . "/public/files/product", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/files/youtube")) {
            mkdir("storage/" . $folder . "/public/files/youtube", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/files/icon")) {
            mkdir("storage/" . $folder . "/public/files/icon", 0777);
        }

        if (!is_dir("storage/" . $folder . "/public/counter")) {
            mkdir("storage/" . $folder . "/public/counter", 0777);
        }

        if (is_dir("files/" . $folder)) {
            $this->mainGlobal->recurse_copy('files/' . $folder, 'storage/' . $folder . '/public/files/' . $folder);
        }

        $this->mainGlobal->recurse_copy('assets/css/pages/' . $folder, 'storage/' . $folder . '/public/assets/css/pages/' . $folder);
        $this->mainGlobal->recurse_copy('files/default/' . $folder, 'storage/' . $folder . '/public/files/default/' . $folder);
        $this->mainGlobal->recurse_copy('files/ads/' . $folder, 'storage/' . $folder . '/public/files/ads/' . $folder);
        $this->mainGlobal->recurse_copy('files/news/' . $folder, 'storage/' . $folder . '/public/files/news/' . $folder);
        $this->mainGlobal->recurse_copy('files/category/' . $folder, 'storage/' . $folder . '/public/files/category/' . $folder);
        $this->mainGlobal->recurse_copy('files/product/' . $folder, 'storage/' . $folder . '/public/files/product/' . $folder);
        $this->mainGlobal->recurse_copy('files/youtube/' . $folder, 'storage/' . $folder . '/public/files/youtube/' . $folder);
        $this->mainGlobal->recurse_copy('files/icon/' . $folder, 'storage/' . $folder . '/public/files/icon/' . $folder);

        if (is_dir('uploads/' . $folder)) {
            $this->mainGlobal->recurse_copy('uploads/' . $folder, 'storage/' . $folder . '/public/uploads/' . $folder);
        }

        $this->mainGlobal->recurse_copy('bannerhtml/' . $folder, 'storage/' . $folder . '/public/bannerhtml/' . $folder);

        if (file_exists('counter/counter-' . $id . '.txt')) {
            copy('counter/counter-' . $id . '.txt', 'storage/' . $folder . '/public/counter/counter-' . $id . '.txt');
        }

        //dump database
        $dbFolder = 'storage/'. $folder . '/database';
        $dbRealFolder = realpath('storage/' . $folder . '/database');
        $listDbGerenal = 'config_core config_group config_kernel form_group layout module_group order_status permissions position word_core password_changes profiles remember_tokens reset_passwords success_logins tmp_module_group_layout tmp_position_module_group tags tmp_news_tags languages';
        $listDbSubdomain = 'background banner banner_html banner_type category clip config_item contact customer_comment customer_message domain form_item layout_config member menu menu_item module_item news newsletter news_category news_menu news_type orders posts price_range product product_content product_detail product_element product_element_detail product_photo setting support tmp_layout_module tmp_news_news_category tmp_news_news_menu tmp_position_module_item tmp_product_category tmp_product_form_item tmp_product_product_element_detail tmp_subdomain_user tmp_banner_banner_type users user_history user_history_transfer tmp_subdomain_language ip_note usually_question answers landing_page tmp_landing_module tmp_type_module subdomain_rating';

        $messageFolder = $this->config->application->messages;
        $langFiles = glob($messageFolder . 'subdomains/' . $folder . '/*.json');
        if (!empty($langFiles)) {
            if (!is_dir("storage/" . $folder . "/app/")) {
                mkdir("storage/" . $folder . "/app", 0777);
            }

            if (!is_dir("storage/" . $folder . "/app/messages")) {
                mkdir("storage/" . $folder . "/app/messages", 0777);
            }

            if (!is_dir("storage/" . $folder . "/app/messages/subdomains")) {
                mkdir("storage/" . $folder . "/app/messages/subdomains", 0777);
            }

            if (!is_dir("storage/" . $folder . "/app/messages/subdomains/" . $folder)) {
                mkdir("storage/" . $folder . "/app/messages/subdomains/" . $folder, 0777);
            }

            foreach ($langFiles as $key => $langFile) {
                copy($langFile, 'storage/' . $folder . '/app/messages/subdomains/' . $folder . '/' . basename($langFile, '.json'). '.json');
            }
        } else {
            $listDbSubdomain .= ' word_item';
        }

        /*system('mysqldump --host="'. $this->config->database->host .'" --user="'. $this->config->database->username .'" --password="'. $this->config->database->password .'" --skip-add-locks --extended-insert=FALSE --compact '. $this->config->database->dbname .' --tables subdomain --where="id = '. $id .'" > '. $dbRealFolder .'/subdomain.sql', $retval);

        system('mysqldump --host="'. $this->config->database->host .'" --user="'. $this->config->database->username .'" --password="'. $this->config->database->password .'" --skip-add-locks --extended-insert=FALSE --compact '. $this->config->database->dbname .' --tables '. $listDbGerenal .' > '. $dbRealFolder .'/gerenal.sql');

        system('mysqldump --host="'. $this->config->database->host .'" --user="'. $this->config->database->username .'" --password="'. $this->config->database->password .'" --skip-add-locks --extended-insert=FALSE --compact '. $this->config->database->dbname .' --tables '. $listDbSubdomain .' --where="subdomain_id = '. $id .'" > '. $dbRealFolder .'/private.sql');*/

        $databaseHost = escapeshellarg($this->config->database->host);
        $databaseUsername = escapeshellarg($this->config->database->username);
        $databasePassword = escapeshellarg($this->config->database->password);

        system('mysqldump --host=' . $databaseHost . ' --user='. $databaseUsername .' --password='. $databasePassword . ' --skip-add-locks --compact ' . $this->config->database->dbname .' --tables ' . $listDbGerenal . ' -r ' . $dbFolder . '/gerenal.sql');

        system('mysqldump --host=' . $databaseHost . ' --user='. $databaseUsername .' --password='. $databasePassword . ' --skip-add-locks --compact '. $this->config->database->dbname .' --tables '. $listDbSubdomain .' --where="subdomain_id = '. $id .'" -r '. $dbFolder .'/private.sql');
  

        system('mysqldump --host=' . $databaseHost . ' --user='. $databaseUsername .' --password='. $databasePassword . ' --skip-add-locks --compact '. $this->config->database->dbname .' --tables subdomain --where="id = '. $id .'" -r '. $dbFolder .'/subdomain.sql');

        $rootPath = realpath('storage/'. $folder);

        // Initialize archive object
        $zipName = 'storage/' . $folder . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($rootPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();

        $response = new Response();
        $file = $folder . '.zip';
        $path = realpath('storage') . '/' . $file;
        $filetype = filetype($path);
        $filesize = filesize($path);
        $response->setHeader("Cache-Control", 'must-revalidate, post-check=0, pre-check=0');
        $response->setHeader("Content-Description", 'File Download');
        $response->setHeader("Content-Type", $filetype);
        $response->setHeader("Content-Length", $filesize);
        $response->setFileToSend($path, str_replace(" ", "-", $file), true);
        $response->send();
        die();
    }

    /**
    * Action Ajax
    * 
    */
    public function updateValueAction()
    {
        //check action post and is ajax
        if ($this->request->isPost() && $this->request->isAjax()) {
        	$result = 1;
            $response = new Response();
            // get all value post on view
            $data = $this->request->getPost();

            // get subdomain by id
            $subDomain = Subdomain::findFirstById($data['id']);
            // check $subDomain is false return error message
            if (!$subDomain) {
                $response->setContent(0);
                return $response;

            }

            $subDomain->note = $data['note'];
            // check save data fail
            if (!$subDomain->save()) {
            	$response->setContent(0);
                return $response;
            }
            $response->setContent($result);
            return $response;
        }
    }

    /**
     * update elastic and delete redis cache
     * @param  interger $id
     * @return bolean
     */
    protected function elasticRedis($id)
    {   
        //update elastic
        $this->elastic_service->updateSubdomain($id);
        // delete redis subdomain child
        $this->redis_service->_deleteHasKey('subdomain', ['subdomain_id' => $id]);
    }
}
