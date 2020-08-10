<?php namespace Modules\Backend\Controllers;

use Modules\Models\Domain;
use Modules\Models\Setting;
use Modules\Models\Subdomain;
use Modules\Models\TmpSubdomainUser;
use Modules\Forms\AdminForm;
use Modules\Forms\SignUpForm;
use Modules\Forms\ForgotPasswordForm;
use Modules\Auth\Auth;
use Modules\Auth\Exception as AuthException;
use Modules\Models\Users;
use Modules\Models\ResetPasswords;
use Modules\Models\Layout;
use Modules\Models\LayoutConfig;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;
use Modules\PhalconVn\General;
use Modules\Mail\MyPHPMailer;
use Statickidz\GoogleTranslate;
use Phalcon\Translate\Adapter\NativeArray;
use Modules\Models\Languages;
use Modules\Models\Banner;
use Modules\Models\SubdomainRating;

class IndexController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
        $this->_subdomain_id = $this->_get_subdomainID();
    }

    /**
     * List subdomain child
     * @return View
     */
    public function indexAction()
    {
        $identity = $this->auth->getIdentity();

        if (!isset($identity)) {
            return $this->response->redirect('auth-login');
        }

        // get current date
        $currentDate = time();
        $date = date("Y-m-d 23:59:59");
        // get current day + 21 days
        $date1 = date('Y-m-d 00:00:00', strtotime('+21 DAY'));

        $subdomain = Subdomain::findFirstByid($identity['subdomain_id']);
        // get subdomain by create id
        $notiDomainExpire =  Subdomain::query()
                ->columns("Modules\Models\Subdomain.*")
                ->where("Modules\Models\Subdomain.create_id = ". $identity['id'])
                ->andWhere("Modules\Models\Subdomain.expired_date > '$date'" )
                ->andWhere("Modules\Models\Subdomain.expired_date <= '$date1'" )
                ->andWhere(" Modules\Models\Subdomain.active = 'Y'" )
                ->orderBy("Modules\Models\Subdomain.expired_date ASC")
                ->execute();
        $setting = Setting::findFirstBySubdomainId($identity['subdomain_id']);
        $numberPage = $this->request->getQuery("page", "int");
        $page_current = ($numberPage > 1) ? $numberPage : 1;
        $limit = 30;
        $offset = $page_current > 1 ? ($page_current - 1) * $limit : 0;
        $listSubdomain = [];

        if ($identity['role'] == 1 || $identity['role'] == 4) {
            $where = '';
            $orderBy = '';
            $notiDomainExpire = Subdomain::query()
                ->columns("Modules\Models\Subdomain.*")
                ->where("Modules\Models\Subdomain.expired_date > '$date'" )
                ->andWhere("Modules\Models\Subdomain.expired_date <= '$date1'" )
                ->andWhere(" Modules\Models\Subdomain.active = 'Y'" )
                ->orderBy("Modules\Models\Subdomain.expired_date ASC")
                ->execute();
            if ($this->request->get('expired') == 'desc') {
                $orderBy .= 'Modules\Models\Subdomain.expired_date DESC,';
            } elseif ($this->request->get('expired') == 'asc') {
                $where = ' AND Modules\Models\Subdomain.active = "Y"';
                $orderBy .= 'Modules\Models\Subdomain.expired_date ASC,';
            }

            if ($this->request->get('balance') == 'desc') {
                $orderBy .= 'u.balance DESC,';
            } elseif ($this->request->get('balance') == 'asc') {
                $orderBy .= 'u.balance ASC,';
            }

            $orderBy .= "Modules\Models\Subdomain.id DESC";

            $subdomains = Subdomain::query()
                    ->columns("Modules\Models\Subdomain.*")
                    ->join("Modules\Models\Users", "Modules\Models\Subdomain.id =  u.subdomain_id", "u")
                    ->where("Modules\Models\Subdomain.id != ". $identity['subdomain_id'] ."$where")
                    ->groupBy("Modules\Models\Subdomain.id")
                    ->limit($limit, $offset)
                    ->orderBy("$orderBy")
                    ->execute();

            $subdomainCount = Subdomain::query()
                    ->columns("COUNT(Modules\Models\Subdomain.id) AS count")
                    ->join("Modules\Models\Users", "Modules\Models\Subdomain.id =  u.subdomain_id", "u")
                    ->where("Modules\Models\Subdomain.id != ". $identity['subdomain_id'] ."$where")
                    ->execute();

            $subdomainChild = Subdomain::find([
                "conditions" => "id != ". $identity["subdomain_id"] ."",
            ]);

            $subdomainChildActive = Subdomain::find([
                "conditions" => "id != ". $identity["subdomain_id"] ." AND active = 'Y'",
            ]);
        } else {
            $where = "tmp.subdomain_id = ". $identity['subdomain_id'] ." OR Modules\Models\Subdomain.create_id = ". $identity["id"] ."";
            $orderBy = "";
            if ($this->request->get('expired') == 'desc') {
                $orderBy .= 'Modules\Models\Subdomain.expired_date DESC,';
            } elseif ($this->request->get('expired') == 'asc') {
                $where = "(tmp.subdomain_id = ". $identity['subdomain_id'] ." OR Modules\Models\Subdomain.create_id = ". $identity["id"] .") AND Modules\Models\Subdomain.active = 'Y'";
                $orderBy .= 'Modules\Models\Subdomain.expired_date ASC,';
            }

            if ($this->request->get('balance') == 'desc') {
                $orderBy .= 'u.balance DESC,';
            } elseif ($this->request->get('balance') == 'asc') {
                $orderBy .= 'u.balance ASC,';
            }

            $orderBy .= "Modules\Models\Subdomain.id DESC";
            $subdomains = Subdomain::query()
                ->columns("Modules\Models\Subdomain.*")
                ->join("Modules\Models\Users", "u.subdomain_id =  Modules\Models\Subdomain.id", "u")
                ->leftJoin("Modules\Models\TmpSubdomainUser", "tmp.user_id =  u.id", "tmp")
                ->where($where)
                ->limit($limit, $offset)
                ->orderBy($orderBy)
                ->execute();

            $subdomainCount = Subdomain::query()
                ->columns("COUNT(Modules\Models\Subdomain.id) AS count")
                ->join("Modules\Models\Users", "u.subdomain_id =  Modules\Models\Subdomain.id", "u")
                ->leftJoin("Modules\Models\TmpSubdomainUser", "tmp.user_id =  u.id", "tmp")
                ->where($where)
                ->execute();
        }

        $listSubdomain[0] = $subdomain->toArray();
        $listSubdomain[0]["count_child"] = count($subdomainChild);
        $listSubdomain[0]["count_child_active"] = count($subdomainChildActive);
        $listSubdomain[0]['layout_id'] = $setting->layout_id;
        $listSubdomain[0]['balance'] = Users::findFirstBySubdomainId($subdomain->id)->balance;
        $listSubdomain[0]['create_name'] = '';
        $listSubdomain[0]['username'] = '';
        $listSubdomain[0]['list_username'] = [];
        $listUserNameManage = TmpSubdomainUser::query()
            ->columns("s.name")
            ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
            ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
            ->where("u.subdomain_id = :subdomain_id:")
            ->bind(["subdomain_id" => $subdomain->id])
            ->execute();
        $arrayUserNameManage = [];
        if (count($listUserNameManage) > 0) {
            foreach ($listUserNameManage as $row) {
                $arrayUserNameManage[] = $row->name;
            }
        }

        $listSubdomain[0]['list_username_manage'] = $arrayUserNameManage;
        $listSubdomain[0]['sum_rate'] = 0;
        if (count($subdomain->subdomainRating) > 0) {
            $listSubdomain[0]['sum_rate'] = SubdomainRating::sum(
                [
                    'column'     => 'rate',
                    'conditions' => "subdomain_id = $subdomain->id",
                ]
            );
        }

        if (count($subdomains) > 0) {
            for ($i = 0; $i < count($subdomains); $i++) {
                $subdomainArrays = $subdomains[$i]->toArray();
                $listSubdomain[$i + 1] = $subdomainArrays;
                $domain = $subdomains[$i]->domain;

                if (count($domain) > 0) {
                    $listSubdomain[$i + 1]["domain"] = $domain->toArray();
                }

                $setting = Setting::findFirstBySubdomainId($subdomainArrays[$i + 1]['id']);
                $listSubdomain[$i + 1]["layout_id"] = ($setting) ? $setting->layout_id : 0;

                $user = Users::findFirst([
                    'columns' => 'username, balance',
                    'conditions' => 'subdomain_id = '. $subdomainArrays['id'] .''
                ]);

                $listUserName = TmpSubdomainUser::query()
                ->columns("u.username")
                ->join("Modules\Models\Users", "u.id =  Modules\Models\TmpSubdomainUser.user_id", "u")
                ->where("Modules\Models\TmpSubdomainUser.subdomain_id = :subdomain_id:")
                ->bind(["subdomain_id" => $subdomainArrays['id']])
                ->execute();

                $listUserNameManage = TmpSubdomainUser::query()
                ->columns("s.name")
                ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                ->where("u.subdomain_id = :subdomain_id:")
                ->bind(["subdomain_id" => $subdomainArrays['id']])
                ->execute();

                $subdomainChild = Subdomain::query()
                ->columns("Modules\Models\Subdomain.id")
                ->join("Modules\Models\Users", "u.id = Modules\Models\Subdomain.create_id", "u")
                ->where("u.subdomain_id = :subdomain_id:")
                ->bind(["subdomain_id" => $subdomainArrays['id']])
                ->execute();

                $subdomainChildActive = Subdomain::query()
                ->columns("Modules\Models\Subdomain.id")
                ->join("Modules\Models\Users", "u.id = Modules\Models\Subdomain.create_id", "u")
                ->where("u.subdomain_id = :subdomain_id:")
                ->andWhere("Modules\Models\Subdomain.active = :active:")
                ->bind(["subdomain_id" => $subdomainArrays['id'],
                    "active" => "Y",
                ])
                ->execute();

                $subdomainParent = Users::query()
                ->columns("s.name")
                ->join("Modules\Models\Subdomain", "s.id = Modules\Models\Users.subdomain_id", "s")
                ->where("Modules\Models\Users.id = :create_id:")
                ->bind(["create_id" => $subdomainArrays['create_id']])
                ->execute();

                $listSubdomain[$i + 1]["count_child"] = count($subdomainChild);
                $listSubdomain[$i + 1]["count_child_active"] = count($subdomainChildActive);
                if (count($subdomainParent)) {
                    $listSubdomain[$i + 1]["create_name"] = $subdomainParent[0]->name;
                } else {
                    $listSubdomain[$i + 1]["create_name"] = '';
                }
                $listSubdomain[$i + 1]["username"] = $user->username;
                $listSubdomain[$i + 1]["balance"] =  $user->balance;

                $arrayUserName = [];
                if (count($listUserName) > 0) {
                    foreach ($listUserName as $row) {
                        $arrayUserName[] = $row->username;
                    }
                }

                $listSubdomain[$i + 1]["list_username"] = $arrayUserName;

                $arrayUserNameManage = [];
                if (count($listUserNameManage) > 0) {
                    foreach ($listUserNameManage as $row) {
                        $arrayUserNameManage[] = $row->name;
                    }
                }

                $listSubdomain[$i + 1]["list_username_manage"] = $arrayUserNameManage;
                $listSubdomain[$i + 1]["sum_rate"] = 0;
                if (isset($subdomainArrays[$i + 1])) {
                    $rate = SubdomainRating::sum(
                        [
                            'column'     => 'rate',
                            'conditions' => "subdomain_id = ". $subdomainArrays[$i + 1]['id'] ."",
                        ]
                    );

                    if (!empty($rate)) {
                        $listSubdomain[$i + 1]["sum_rate"] = $rate;
                    }
                }
            }
        }

        $total = $subdomainCount[0]->count;
        $total_pages = ceil($total / $limit);
        $qParam = true;
        $urlPage = '/' . ACP_NAME . '/' . $this->_getControllerName();
        if ($this->request->get('expired') != '' || $this->request->get('balance') != '') {
            if ($this->request->get('expired') == 'desc') {
                $urlPage .= '?expired=desc&';
            } elseif ($this->request->get('expired') == 'asc') {
                $urlPage .= '?expired=asc&';
            }

            if ($this->request->get('balance') == 'desc') {
                $urlPage .= '?balance=desc&';
            } elseif ($this->request->get('balance') == 'asc') {
                $urlPage .= '?balance=asc&';
            }

            $qParam = false;
        }


        $this->view->module_name = 'Danh sách website';
        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->ipAdress = $this->request->getServerAddress();
        $this->view->listSubdomain = $listSubdomain;
        $this->view->activeSslAmount = $this->_config_kernel->_cf_kernel_active_ssl;
        $this->view->page_current = $page_current;
        $this->view->total = $total;
        $this->view->total_pages = $total_pages;
        $this->view->url_page = $urlPage;
        $this->view->qParam = $qParam;
        $this->view->notiDomainExpire = $notiDomainExpire;
        $this->view->currentDate = $currentDate;
        if ($identity['role'] != 4 || getenv('APP_ENV') == 'development') {
            $this->view->pick($this->_getControllerName() . '/_index');
        }
    }

    /**
     * Search subdomain
     * @return View
     */
    public function searchAction()
    {
        $identity = $this->auth->getIdentity();
        if ($this->request->isPost() == true) {
            if ($this->request->isAjax() == true) {
                $this->view->setTemplateBefore('search');
                $keyword = $this->request->getPost('keyword', 'string');
                $setting = Setting::findFirstBySubdomainId($identity['subdomain_id']);
                $listSubdomain = [];
                if ($identity['role'] == 1 || $identity['role'] == 4) {
                    $subdomain = Subdomain::findFirstByid($identity['subdomain_id']);
                    $listSubdomain[0] = $subdomain->toArray();
                    $subdomainChild = Subdomain::find([
                        "conditions" => "id != ". $identity["subdomain_id"] ."",
                    ]);
                    $subdomainChildActive = Subdomain::find([
                        "conditions" => "id != ". $identity["subdomain_id"] ." AND active = 'Y'",
                    ]);
                    $listSubdomain[0]["count_child"] = count($subdomainChild);
                    $listSubdomain[0]["count_child_active"] = count($subdomainChildActive);
                    $listSubdomain[0]['layout_id'] = $setting->layout_id;
                    $listSubdomain[0]['balance'] = Users::findFirstBySubdomainId($subdomain->id)->balance;
                    $listSubdomain[0]['create_name'] = '';
                    $listSubdomain[0]['username'] = '';
                    $listSubdomain[0]['list_username'] = [];
                    $listUserNameManage = TmpSubdomainUser::query()
                        ->columns("s.name")
                        ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                        ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                        ->where("u.subdomain_id = :subdomain_id:")
                        ->bind(["subdomain_id" => $subdomain->id])
                        ->execute();
                    $arrayUserNameManage = [];
                    if (count($listUserNameManage) > 0) {
                        foreach ($listUserNameManage as $row) {
                            $arrayUserNameManage[] = $row->name;
                        }
                    }

                    $listSubdomain[0]['list_username_manage'] = $arrayUserNameManage;
                    $listSubdomain[0]['sum_rate'] = 0;
                    if (count($subdomain->subdomainRating) > 0) {
                        $listSubdomain[0]['sum_rate'] = SubdomainRating::sum(
                            [
                                'column'     => 'rate',
                                'conditions' => "subdomain_id = $subdomain->id",
                            ]
                        );
                    }

                    $subdomains = Subdomain::query()
                    ->columns("Modules\Models\Subdomain.*")
                    ->leftJoin("Modules\Models\Domain", "d.subdomain_id =  Modules\Models\Subdomain.id", "d")
                    ->where("(Modules\Models\Subdomain.name LIKE '%". $keyword ."%' OR d.name LIKE '%". $keyword ."%') AND Modules\Models\Subdomain.id != ". $identity['subdomain_id'] ."")
                    ->groupBy("Modules\Models\Subdomain.id")
                    ->orderBy("Modules\Models\Subdomain.id DESC")
                    ->execute();
                } else {
                    $subdomain = Subdomain::findFirstByid($identity['subdomain_id']);
                    $listSubdomain[0] = $subdomain->toArray();
                    $listSubdomain[0]["count_child"] = 0;
                    $listSubdomain[0]["count_child_active"] = 0;
                    $listSubdomain[0]['layout_id'] = $setting->layout_id;
                    $listSubdomain[0]['balance'] = Users::findFirstBySubdomainId($subdomain->id)->balance;
                    $listSubdomain[0]['create_name'] = '';
                    $listSubdomain[0]['username'] = '';
                    $listSubdomain[0]['list_username'] = [];
                    $listSubdomain[0]['list_username_manage'] = [];
                    if (count($subdomain->subdomainRating) > 0) {
                        $listSubdomain[0]['sum_rate'] = SubdomainRating::sum(
                            [
                                'column'     => 'rate',
                                'conditions' => "subdomain_id = $subdomain->id",
                            ]
                        );
                    }

                    $subdomains = Subdomain::query()
                    ->columns("Modules\Models\Subdomain.*")
                    ->leftJoin("Modules\Models\Domain", "d.subdomain_id =  Modules\Models\Subdomain.id", "d")
                    ->leftJoin("Modules\Models\Users", "u.subdomain_id =  Modules\Models\Subdomain.id", "u")
                    ->leftJoin("Modules\Models\TmpSubdomainUser", "tmp.user_id =  u.id", "tmp")
                    ->where("(Modules\Models\Subdomain.name LIKE '%". $keyword ."%' OR d.name LIKE '%". $keyword ."%') AND (tmp.subdomain_id = ". $identity['subdomain_id'] ." OR Modules\Models\Subdomain.create_id = ". $identity['id'] .")")
                    ->groupBy("Modules\Models\Subdomain.id")
                    ->orderBy("Modules\Models\Subdomain.id DESC")
                    ->execute();
                }
                
                
                if (count($subdomains) > 0) {
                    $subdomains = $subdomains->toArray();
                    for ($i = 0; $i < count($subdomains); $i++) {
                        $listSubdomain[$i + 1] = $subdomains[$i];
                        $domain = Domain::find([
                            "columns" => "id, name",
                            "conditions" => "subdomain_id = ". $subdomains[$i]['id'] ."",
                            "order" => "id DESC"
                        ]);

                        if (count($domain) > 0) {
                            $listSubdomain[$i + 1]["domain"] = $domain->toArray();
                        }

                        $setting = Setting::findFirstBySubdomainId($subdomains[$i + 1]['id']);
                        $listSubdomain[$i + 1]["layout_id"] = ($setting) ? $setting->layout_id : 0;

                        $user = Users::findFirst([
                            'columns' => 'username, balance',
                            'conditions' => 'subdomain_id = '. $subdomains[$i]['id'] .''
                        ]);

                        $listUserName = TmpSubdomainUser::query()
                        ->columns("u.username")
                        ->join("Modules\Models\Users", "u.id =  Modules\Models\TmpSubdomainUser.user_id", "u")
                        ->where("Modules\Models\TmpSubdomainUser.subdomain_id = :subdomain_id:")
                        ->bind(["subdomain_id" => $subdomains[$i]['id']])
                        ->execute();

                        $listUserNameManage = TmpSubdomainUser::query()
                        ->columns("s.name")
                        ->join("Modules\Models\Users", "u.id = Modules\Models\TmpSubdomainUser.user_id", "u")
                        ->join("Modules\Models\Subdomain", "s.id = Modules\Models\TmpSubdomainUser.subdomain_id", "s")
                        ->where("u.subdomain_id = :subdomain_id:")
                        ->bind(["subdomain_id" => $subdomains[$i]['id']])
                        ->execute();

                        $subdomainChild = Subdomain::query()
                        ->columns("Modules\Models\Subdomain.id")
                        ->join("Modules\Models\Users", "u.id = Modules\Models\Subdomain.create_id", "u")
                        ->where("u.subdomain_id = :subdomain_id:")
                        ->bind(["subdomain_id" => $subdomains[$i]['id']])
                        ->execute();

                        $subdomainChildActive = Subdomain::query()
                        ->columns("Modules\Models\Subdomain.id")
                        ->join("Modules\Models\Users", "u.id = Modules\Models\Subdomain.create_id", "u")
                        ->where("u.subdomain_id = :subdomain_id:")
                        ->andWhere("Modules\Models\Subdomain.active = :active:")
                        ->bind(["subdomain_id" => $subdomains[$i]['id'],
                            "active" => "Y",
                        ])
                        ->execute();

                        $subdomainParent = Users::query()
                        ->columns("s.name")
                        ->join("Modules\Models\Subdomain", "s.id = Modules\Models\Users.subdomain_id", "s")
                        ->where("Modules\Models\Users.id = :create_id:")
                        ->bind(["create_id" => $subdomains[$i]['create_id']])
                        ->execute();

                        $listSubdomain[$i + 1]["count_child"] = count($subdomainChild);
                        $listSubdomain[$i + 1]["count_child_active"] = count($subdomainChildActive);
                        if (count($subdomainParent)) {
                            $listSubdomain[$i + 1]["create_name"] = $subdomainParent[0]->name;
                        } else {
                            $listSubdomain[$i + 1]["create_name"] = '';
                        }
                        $listSubdomain[$i + 1]["username"] = $user->username;
                        $listSubdomain[$i + 1]["balance"] =  $user->balance;

                        $arrayUserName = [];
                        if (count($listUserName) > 0) {
                            foreach ($listUserName as $row) {
                                $arrayUserName[] = $row->username;
                            }
                        }
                        $listSubdomain[$i + 1]["list_username"] = $arrayUserName;

                        $arrayUserNameManage = [];
                        if (count($listUserNameManage) > 0) {
                            foreach ($listUserNameManage as $row) {
                                $arrayUserNameManage[] = $row->name;
                            }
                        }

                        $listSubdomain[$i + 1]["list_username_manage"] = $arrayUserNameManage;
                        $listSubdomain[$i + 1]["sum_rate"] = 0;
                        if (isset($subdomains[$i + 1])) {
                            $rate = SubdomainRating::sum(
                                [
                                    'column'     => 'rate',
                                    'conditions' => "subdomain_id = ". $subdomains[$i + 1]['id'] ."",
                                ]
                            );

                            if (!empty($rate)) {
                                $listSubdomain[$i + 1]["sum_rate"] = $rate;
                            }
                        }
                    }
                }
                // $this->view->subdomain = $subdomain;
                $this->view->list_sub_domain = $listSubdomain;
                $this->view->activeSslAmount = $this->_config_kernel->_cf_kernel_active_ssl;
                if ($identity['role'] != 4 || getenv('APP_ENV') == 'development') {
                    $this->view->pick($this->_getControllerName() . '/_search');
                }
            }
        }
    }

    public function checkUrlDuplicateAction()
    {
        if ($this->request->isPost()) {
            if ($this->request->isAjax()) {
                $id = $this->request->getPost('id');
                $table = $this->request->getPost('table');
                $url = $this->request->getPost('url');

                if ($id != 0) {
                    $result = ($this->mainGlobal->validateUrlPageUpdate($id, $url, $table) == true) ?  1 : 0;
                } else {
                    $result = ($this->mainGlobal->validateUrlPageCreate($url) == true) ? 1 : 0;
                }

                echo $result;
            }
        }

        $this->view->disable();
    }

    public function loginAction()
    {
        $this->view->setTemplateBefore('login');
        $this->assets->addCss('backend/dist/css/adminLogin.css');
        
        $form = new AdminForm();
        try {
            if (!$this->request->isPost()) {
                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }

                $identity = $this->auth->getIdentity();
                if (isset($identity)) {
                    return $this->response->redirect(ACP_NAME);
                }
            } else {
                //valdate and print error in LoginForm function messages
                if ($form->isValid($this->request->getPost())==true) {
                    $gerenal = new General();
                    $subdomain = $this->mainGlobal->checkDomain();

                    $this->auth->check(array(
                        'subdomain_id' => $subdomain->id,
                        'username' => $this->request->getPost('username'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ));
                    $url_redirect_back = $this->session->get('url_redirect_back');
                    $this->session->remove('url_redirect_back');
                    return $this->response->redirect(ACP_NAME . '/' . $url_redirect_back);
                }
            }
        } catch (Exception $e) {
            $this->flash->error($e->getMessage());
        }
        
        $messageAdminFile = $this->word_translate->getMessageAdminFile();
        $this->view->form = $form;
        $this->view->setVar('messageAdFile', $messageAdminFile);
    }

    public function loginSubdomainAction($id)
    {
        $identity = $this->auth->getIdentity();

        if ($identity['role'] == 1) {
            $conditions = "id = $id";
        } else {
            $conditions = "(id = $id AND id = ". $identity['subdomain_id'] .") OR (id = $id AND create_id = ". $identity['id'] .")";
        }

        $subdomain = Subdomain::findFirst([
            "conditions" => $conditions
        ]);

        if (!$subdomain) {
            $tmpSubdomainUser = TmpSubdomainUser::findBySubdomainId($identity['subdomain_id']);
            if (count($tmpSubdomainUser) > 0) {
                $arrayUserId = [];
                foreach ($tmpSubdomainUser as $tmp) {
                    $arrayUserId[] = $tmp->user_id;
                }
                $arrayUserId = (count($arrayUserId) > 1) ? implode(",", $arrayUserId) : $arrayUserId[0];
                $user = Users::findFirst(
                    array(
                        "conditions" => "active = 'Y' AND id IN ($arrayUserId) AND subdomain_id = $id",
                    )
                );
                if ($user) {
                    $subdomain = Subdomain::findFirstByid($user->subdomain_id);
                }
            }
        }


        if ($subdomain) {
            $host = ($subdomain->name != '@') ? $subdomain->name . '.' . ROOT_DOMAIN : $_SERVER['HTTP_HOST'];
            $user = Users::findFirstBySubdomainId($id);
            $this->session->set('subdomain-child', [
                'subdomain_id' => $id,
                'subdomain_name' => $subdomain->name,
                'folder' => $subdomain->folder,
                'not_thumb' => $subdomain->not_thumb,
                'host' => $host,
                'role' => $user->role
            ]);
            $gerenal = new General();
            $this->flashSession->success('Đăng nhập website ' . $subdomain->name . '.' . $gerenal->get_domain(HTTP_HOST) . ' thành công');
        }
        
        return $this->response->redirect(ACP_NAME);
    }

    public function forgotPasswordAction()
    {
        echo("khoi phuc mat khau");
    }

    public function logoutAction()
    {
        $this->auth->remove();

        return $this->response->redirect('auth-login');
    }

    public function createfolderAction()
    {
        if (!is_dir("files/module")) {
            mkdir("files/module", 0777);
        }

        if (!is_dir("files/001")) {
            mkdir("files/001", 0777);
        }
        if (!is_dir("files/default/001")) {
            mkdir("files/default/001", 0777);
        }
        if (!is_dir("files/ads/001")) {
            mkdir("files/ads/001", 0777);
        }
        if (!is_dir("files/news/001")) {
            mkdir("files/news/001", 0777);
        }
        if (!is_dir("files/youtube/001")) {
            mkdir("files/youtube/001", 0777);
        }
        if (!is_dir("files/product/001")) {
            mkdir("files/product/001", 0777);
        }
        if (!is_dir("files/category/001")) {
            mkdir("files/category/001", 0777);
        }
    }

    public function copyAction()
    {
        $file = "style.css";
        file_put_contents($file, "");
        $layoutConfig = LayoutConfig::findFirstById(1);
        $css = $layoutConfig->css;
        if (file_put_contents($file, $css, FILE_APPEND | LOCK_EX)) {
            echo "success";
        } else {
            echo "error";
        }
        $this->view->disable();
    }

    public function deleleCacheAction()
    {
        
        $this->flashSession->success('Xóa cache thành công');
        $url = ACP_NAME;
        $this->response->redirect($url);
    }

    public function deleteFileAction()
    {
        if ($this->request->getPost() != '') {
            $url = substr($this->request->getPost('url'), 1);
            if (unlink($url)) {
                echo 1;
            } else {
                echo 0;
            }
        }
        $this->view->disable();
    }

    public function versionAction()
    {
        echo \Phalcon\Version::get();
        $this->view->disable();
    }

    public function noPermissionAction()
    {
        $identity = $this->auth->getIdentity();

        if (!isset($identity)) {
            return $this->response->redirect('auth-login');
        }
        
        $this->flash->warning('Bạn không có quyền truy cập module này');
    }
}
