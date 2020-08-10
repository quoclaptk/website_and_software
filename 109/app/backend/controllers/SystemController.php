<?php

namespace Modules\Backend\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Text;
use Phalcon\Security\Random;
use Phalcon\Image\Adapter\GD;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;

use Modules\Models\IpNote;
use Phalcon\Http\Response;
use Modules\Models\Users;
use Modules\Models\Subdomain;

class SystemController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Quản lý hệ thống';
    }

    public function ipAccessAction()
    {
        $identity = $this->auth->getIdentity();
        $subdomainId = $this->_get_subdomainID();
        $user = Users::findFirstBySubdomainId($subdomainId);
        $file = $user->role == 1 ? 'counter_ip/counter-ip.txt' : 'counter_ip/counter-ip-' . $subdomainId . '.txt';
        if (file_exists($file)) {
            $data = '';
            $data .= file_get_contents($file);
            if ($user->role != 1) {
                $agencySubdomains = Subdomain::findByCreateId($user->id);
                if ($agencySubdomains->count() > 0) {
                    foreach ($agencySubdomains as $agencySubdomain) {
                        if (file_exists('counter_ip/counter-ip-' . $agencySubdomain->id . '.txt')) {
                            $data .= file_get_contents('counter_ip/counter-ip-' . $agencySubdomain->id . '.txt');
                        }
                    }
                }
            }

            $ipLists = explode(";", $data);
            $ipLists = array_filter($ipLists);
            $ipLists = array_reverse($ipLists);
            $ipLists = array_chunk($ipLists, 100);
            // statistic with ip
            $ipListInfos = [];
            foreach ($ipLists as $ipListChunk) {
                foreach ($ipListChunk as $ipList) {
                    $ipListElms = explode('||', $ipList);
                    if (!empty($ipListElms[1])) {
                        $ipListInfos[$ipListElms[1]][] = $ipListElms;
                    }
                }
            }
            
            $ipAcesses = [];
            $i = 0;


            foreach ($ipListInfos as $key => $ipListInfo) {
                if ($i < 1000) {
                    $ipAcesses[$key]['total'] = count($ipListInfo);

                    //get date total access
                    $todayCount = 0;
                    $yesterdayCount = 0;
                    $weekCount = 0;
                    $monthCount = 0;
                    $yearCount = 0;
                    $ipAcesses[$key]['url'] = [];

                    foreach ($ipListInfo as $iplistInf) {
                        if (date('d', strtotime($iplistInf[0])) == date('d')) {
                            $todayCount++;
                        }

                        if (date('d', strtotime($iplistInf[0])) == date('d', strtotime("-1 days"))) {
                            $yesterdayCount++;
                        }

                        if (date('W', strtotime($iplistInf[0])) == date('W')) {
                            $weekCount++;
                        }

                        if (date('n', strtotime($iplistInf[0])) == date('n')) {
                            $monthCount++;
                        }

                        if (date('Y', strtotime($iplistInf[0])) == date('Y')) {
                            $yearCount++;
                        }

                        $ipAcesses[$key]['url'][$iplistInf[2]][] = $iplistInf;
                    }

                    $ipAcesses[$key]['today'] = $todayCount;
                    $ipAcesses[$key]['yesterday'] = $yesterdayCount;
                    $ipAcesses[$key]['week'] = $weekCount;
                    $ipAcesses[$key]['month'] = $monthCount;
                    $ipAcesses[$key]['year'] = $yearCount;

                    if (!empty($ipAcesses[$key]['url'])) {
                        foreach ($ipAcesses[$key]['url'] as $keyUrl => $url) {
                            $ipAcesses[$key]['url'][$keyUrl] = count($url);
                        }

                        $ipAcesses[$key]['url'] = json_encode($ipAcesses[$key]['url']);
                    }

                    $ipNote = IpNote::findFirst([
                        'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND ip_address = "'. $key .'"'
                    ]);

                    if ($ipNote) {
                        $ipAcesses[$key]['note'] = $ipNote->note;
                    }
                }

                $i++;
            }

            //sort ip width today
            uasort($ipAcesses, function ($a, $b) {
                return $a['today'] < $b['today'];
            });
            
            $this->view->ipAcesses = $ipAcesses;
        }

        $this->view->title_bar = 'Thống kê truy cập';
    }

    public function ipAccessAdsAction()
    {
        $identity = $this->auth->getIdentity();
        $subdomainId = $this->_get_subdomainID();
        $user = Users::findFirstBySubdomainId($subdomainId);
        $file = $user->role == 1 ? 'counter_ip/counter-ip.txt' : 'counter_ip/counter-ip-' . $subdomainId . '.txt';
        if (file_exists($file)) {
            $data = '';
            $data .= file_get_contents($file);
            if ($user->role != 1) {
                $agencySubdomains = Subdomain::findByCreateId($user->id);
                if ($agencySubdomains->count() > 0) {
                    foreach ($agencySubdomains as $agencySubdomain) {
                        if (file_exists('counter_ip/counter-ip-' . $agencySubdomain->id . '.txt')) {
                            $data .= file_get_contents('counter_ip/counter-ip-' . $agencySubdomain->id . '.txt');
                        }
                    }
                }
            }

            $ipLists = explode(";", $data);
            $ipLists = array_filter($ipLists);
            $ipLists = array_reverse($ipLists);
            $ipLists = array_chunk($ipLists, 100);
            // statistic with ip
            $ipListInfos = [];
            foreach ($ipLists as $ipListChunk) {
                foreach ($ipListChunk as $ipList) {
                    $ipListElms = explode('||', $ipList);
                    if (isset($ipListElms[2])) {
                        $url = $ipListElms[2];
                        $parts = parse_url($url);
                        if (isset($parts['query'])) {
                            parse_str($parts['query'], $query);
                            if (!empty($ipListElms[1]) && isset($query['gclid'])) {
                                $ipListInfos[$ipListElms[1]][] = $ipListElms;
                            }
                        }
                    }
                }
            }
            
            $ipAcesses = [];
            $i = 0;

            foreach ($ipListInfos as $key => $ipListInfo) {
                if ($i < 1000) {
                    $ipAcesses[$key]['total'] = count($ipListInfo);

                    //get date total access
                    $todayCount = 0;
                    $yesterdayCount = 0;
                    $weekCount = 0;
                    $monthCount = 0;
                    $yearCount = 0;
                    $ipAcesses[$key]['url'] = [];

                    foreach ($ipListInfo as $iplistInf) {
                        if (date('d', strtotime($iplistInf[0])) == date('d')) {
                            $todayCount++;
                        }

                        if (date('d', strtotime($iplistInf[0])) == date('d', strtotime("-1 days"))) {
                            $yesterdayCount++;
                        }

                        if (date('W', strtotime($iplistInf[0])) == date('W')) {
                            $weekCount++;
                        }

                        if (date('n', strtotime($iplistInf[0])) == date('n')) {
                            $monthCount++;
                        }

                        if (date('Y', strtotime($iplistInf[0])) == date('Y')) {
                            $yearCount++;
                        }

                        $ipAcesses[$key]['url'][$iplistInf[2]][] = $iplistInf;
                    }

                    $ipAcesses[$key]['today'] = $todayCount;
                    $ipAcesses[$key]['yesterday'] = $yesterdayCount;
                    $ipAcesses[$key]['week'] = $weekCount;
                    $ipAcesses[$key]['month'] = $monthCount;
                    $ipAcesses[$key]['year'] = $yearCount;

                    if (!empty($ipAcesses[$key]['url'])) {
                        foreach ($ipAcesses[$key]['url'] as $keyUrl => $url) {
                            $ipAcesses[$key]['url'][$keyUrl] = count($url);
                        }

                        $ipAcesses[$key]['url'] = json_encode($ipAcesses[$key]['url']);
                    }

                    $ipNote = IpNote::findFirst([
                        'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND ip_address = "'. $key .'"'
                    ]);

                    if ($ipNote) {
                        $ipAcesses[$key]['note'] = $ipNote->note;
                    }
                }
                
                $i++;
            }

            //sort ip width today
            uasort($ipAcesses, function ($a, $b) {
                return $a['today'] < $b['today'];
            });

            
            $this->view->ipAcesses = $ipAcesses;
        }

        $this->view->title_bar = 'Thống kê IP click Google Ads';
        $this->view->pick($this->_getControllerName() . '/ipAccess');
    }

    public function linkAccessAdsAction()
    {
        $identity = $this->auth->getIdentity();
        $subdomainId = $this->_get_subdomainID();
        $user = Users::findFirstBySubdomainId($subdomainId);
        $file = $user->role == 1 ? 'counter_ip/counter-ip.txt' : 'counter_ip/counter-ip-' . $subdomainId . '.txt';
        if (file_exists($file)) {
            $data = '';
            $data .= file_get_contents($file);
            if ($user->role != 1) {
                $agencySubdomains = Subdomain::findByCreateId($user->id);
                if ($agencySubdomains->count() > 0) {
                    foreach ($agencySubdomains as $agencySubdomain) {
                        if (file_exists('counter_ip/counter-ip-' . $agencySubdomain->id . '.txt')) {
                            $data .= file_get_contents('counter_ip/counter-ip-' . $agencySubdomain->id . '.txt');
                        }
                    }
                }
            }

            $ipLists = explode(";", $data);
            $ipLists = array_filter($ipLists);
            $ipLists = array_chunk($ipLists, 100);
            // statistic with ip
            $ipListInfos = [];
            foreach ($ipLists as $ipListChunk) {
                foreach ($ipListChunk as $ipList) {
                    $ipListElms = explode('||', $ipList);
                    if (isset($ipListElms[2])) {
                        $url = $ipListElms[2];
                        $parts = parse_url($url);
                        if (isset($parts['query'])) {
                            parse_str($parts['query'], $query);
                            $noteUrl = $parts['scheme'] . '://' . $parts['host'] . $parts['path'];
                            if (!empty($ipListElms[1]) && isset($query['gclid'])) {
                                $ipListInfos[$noteUrl][] = $ipListElms;
                            }
                        }
                    }
                }
            }

            $ipAcesses = [];
            $i = 0;

            foreach ($ipListInfos as $key => $ipListInfo) {
                $ipAcesses[$key]['total'] = count($ipListInfo);

                //get date total access
                $todayCount = 0;
                $yesterdayCount = 0;
                $weekCount = 0;
                $monthCount = 0;
                $yearCount = 0;
                $ipAcesses[$key]['ip'] = [];

                foreach ($ipListInfo as $iplistInf) {
                    if (date('d', strtotime($iplistInf[0])) == date('d')) {
                        $todayCount++;
                    }

                    if (date('d', strtotime($iplistInf[0])) == date('d', strtotime("-1 days"))) {
                        $yesterdayCount++;
                    }

                    if (date('W', strtotime($iplistInf[0])) == date('W')) {
                        $weekCount++;
                    }

                    if (date('n', strtotime($iplistInf[0])) == date('n')) {
                        $monthCount++;
                    }

                    if (date('Y', strtotime($iplistInf[0])) == date('Y')) {
                        $yearCount++;
                    }

                    $ipAcesses[$key]['ip'][$iplistInf[1]][] = $iplistInf;
                }

                $ipAcesses[$key]['today'] = $todayCount;
                $ipAcesses[$key]['yesterday'] = $yesterdayCount;
                $ipAcesses[$key]['week'] = $weekCount;
                $ipAcesses[$key]['month'] = $monthCount;
                $ipAcesses[$key]['year'] = $yearCount;

                if (!empty($ipAcesses[$key]['ip'])) {
                    foreach ($ipAcesses[$key]['ip'] as $keyUrl => $ip) {
                        $ipAcesses[$key]['ip'][$keyUrl] = count($ip);
                    }

                    $ipAcesses[$key]['ip'] = json_encode($ipAcesses[$key]['ip']);
                }

                $ipNote = IpNote::findFirst([
                    'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND ip_address = "'. $key .'"'
                ]);

                if ($ipNote) {
                    $ipAcesses[$key]['note'] = $ipNote->note;
                }
                
                $i++;
            }


            //sort ip width today
            uasort($ipAcesses, function ($a, $b) {
                return $a['today'] < $b['today'];
            });

            $this->view->ipAcesses = $ipAcesses;
        }

        $this->view->title_bar = 'Thống kê Link click Google Ads';
        $this->view->pick($this->_getControllerName() . '/ipAccess');
    }

    public function viewListUrlIpAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $url = $this->request->getPost('url');
            arsort($url);
            $this->view->setTemplateBefore('viewListUrl');
            $this->view->url = $url;
            $this->view->pick($this->_getControllerName() . '/viewListUrl');
        }
    }

    public function saveIpNoteAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $note = $this->request->getPost('note');
            $ip = $this->request->getPost('ip');
            $identity = $this->auth->getIdentity();
            $ipNote = IpNote::findFirst([
                'conditions' => 'ip_address  = "'. $ip .'" AND subdomain_id = '. $this->_get_subdomainID() .''
            ]);
            if (!$ipNote) {
                $ipNote = new IpNote();
            }

            $ipNote->ip_address = $ip;
            $ipNote->note = $note;
            $ipNote->subdomain_id = $this->_get_subdomainID();
            $response = new Response();
            if ($ipNote->save()) {
                $result = 1;
            } else {
                $result = 0;
            }

            $response->setContent($result);
            return $response;
        }
    }
}
