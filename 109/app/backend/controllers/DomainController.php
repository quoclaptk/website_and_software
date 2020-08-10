<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Domain;
use Modules\Forms\DomainForm;
use Modules\PhalconVn\DirectAdmin;

class DomainController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Quản lý tên miền';
        $this->directAdmin = new DirectAdmin();
        $this->directAdmin->connect($this->config->directAdmin->ip, $this->config->directAdmin->port);
        $this->directAdmin->set_login($this->config->directAdmin->username, $this->config->directAdmin->password);
        $this->directAdmin->set_method('get');
    }

    public function sslAction()
    {
        $this->view->disable();
        $this->directAdmin->set_method('POST');
        $domains = [
            'sieuthiy.com',
            'diennuochoaphat.com',
            'thuyngocmedia.com',
            'tienads.com',
            'googleso1.com',
            'tkweb24.com',
            'nhunggoogle.com',
            'lentopgoogle.com',
            '4000web.com',
            'phuocweb.com',
            'maycatnhomcnc.com',
            'xemayduynham.com',
            'daunga.com',
            'dienlanhtrungthanh.com',
            'diencotieudung.com',
            'chongthamuytinodanang.com',
            'vantaithanhhungphat.com',
            'webdep247.com',
            'thongcongnghetdalat.com',
            'batdongsanmatbienquynhon.com',
            'gianphoi-hoaphat.com',
            'ghoaphat.com',
            'thuyvan.net',
            'insaigonso.net',
            'cameramientay.net',
            'camlyads.com',
            'dogonpt.com',
            'nhadepcatbien.com',
            'tongdailyxetaihyundai.com',
            'lamweb123.com',
            'thethaohaanh.com',
            'cosplayvayngusexy.com',
            'thanhhung02422633733.com',
            'atg-made-in-germany.com',
            'thietkewebredep.com',
            'vantaithanhhung0966971838.com',
            'lapmangbinhduong.com',
            'taiads.com',
            'nhaphanphoithuocdietcontrung.com',
            'cancongnghiepttic.com',
            'ongthanden.com',
            'giasutainhakiengiang.com',
            'topwebvn.com',
            'nhalatreviet.com',
            'maixepquoclinh.com',
            'inhoadonvn.com',
            'thuexemaysauly.com',
            'cokhiquangphat.com',
            'mailanguyenhung.com',
            'thicongnhala.com',
            'caotactiasua.com',
            'nguyenkhangld.com',
            'caodanbaoanh.com',
            'chuyenlambanggiare.com',
            'quangcaothietkeweb.com',
            '1freelancer.com',
            'atg.com.vn',
            'giasudanang.com.vn',
            'clbtuongduong.vn',
            '109.vn',
            'noithatdaithanh.vn',
            'phomaique.vn',
            'vnpt-dongnai.vn',
            'thuexequangbinh.vn',
            'vnpt-hoadondientu.vn',
            'calispa.vn',
            'giaxe-mazda.vn',
            'novaspa.vn',
            'congtythuhoino.com',
            'giatotxehoi.com',
            'giuongsathoaphat.com',
            'lamweb247.com',
            'lapwebgiare.com',
            'noithatthuyduy.com',
            'thienanphuc.com',
            'truckimbaophat.com',
            'hoquanweb.com',
            'dienlanhthudaumot.com',
            'culaochamtour1.com',
            'vaytinchap.co',
            'lambangchuan.net',
            'diennuocmiennam.net',
            'manhphatmobile.net',
            'dailyhoaphatsaigon.net',
            'bangcap24h.net',
            'nhalatreviet.net',
            'songweb.net',
            'vinfastanthai.net',
            'vinfastanthai.vn',
            'hoadondientuvat.vn',
            'trungtamgiasudanang.edu.vn',
            'atg-made-in-germany.de',
            'hiqua-made-in-germany.de',
            'hoadondientu.company',
            'taxisieuredn43.com'
        ];
        $leSelects = [];
        $leSelects = [
            'domain' => $this->config->directAdmin->hostname,
            'action' => 'save',
            'type' => 'create',
            'request' => 'letsencrypt',
            'name' => 'www.' . $this->config->directAdmin->hostname,
            'email' => 'admin@lamweb123.com',
            'keysize' => 4096,
            'encryption' => 'sha256',
        ];
        foreach ($domains as $key => $domain) {
            $leSelects['le_select' . $key] = $domain;
        }

        $this->directAdmin->query('/CMD_API_SSL', array(
            $leSelects
        ));
        
        $res = $this->directAdmin->fetch_parsed_body();
        echo '<pre>'; print_r($res); echo '</pre>';
        $this->view->disable();
    }

    public function editAction($id)
    {
        $this->view->setTemplateBefore('form');
        $item = Domain::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect('/' . ACP_NAME);
        }

        $this->directAdmin->query('/CMD_API_DOMAIN_POINTER', array(
            'domain' => $this->config->directAdmin->hostname,
            'action' => 'delete',
            'select0' => $item->name
        ));
        $this->directAdmin->fetch_parsed_body();

        $form = new DomainForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $item->delete();
            $item->assign([
                "subdomain_id" => 122,
                "name" => $this->request->getPost("name")
            ]);
            if ($item->save()) {
                
                $this->directAdmin->query('/CMD_API_DOMAIN_POINTER', array(
                    'domain' => $this->config->directAdmin->hostname,
                    'action' => 'add',
                    'from' => $this->request->getPost('name'),
                    'alias' => 'yes',
                ));
                $this->directAdmin->fetch_parsed_body();
                return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/' . $this->_getActionName() . '/' . $item->id . '?message=success');
            }
        }

        $this->view->title_bar = "Cập nhật domain";
        $this->view->item = $item;
        $this->view->form = $form;
        $this->view->message = $this->request->get('message');
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function addAllDomainPointerAction()
    {
        $domains = Domain::find();

        foreach ($domains as $domain) {
            $this->directAdmin->query('/CMD_API_DOMAIN_POINTER', array(
                'domain' => $this->config->directAdmin->hostname,
                'action' => 'add',
                'from' => $domain->name,
                'alias' => 'yes',
            ));
        }
    }

    public function deleteAction($id)
    {
        $identity = $this->auth->getIdentity();
        $item = Domain::findFirst([
            "conditions" => "id = $id"
        ]);
        $this->directAdmin->query('/CMD_API_DOMAIN_POINTER', array(
            'domain' => $this->config->directAdmin->hostname,
            'action' => 'delete',
            'select0' => $item->name
        ));
        $this->directAdmin->fetch_parsed_body();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect('/' . ACP_NAME);
        }
        if ($item->delete()) {
            $this->elastic_service->updateSubdomain($item->subdomain_id);
            $this->flashSession->success("Xóa tên miền ". $item->name ." thành công");
            $this->response->redirect('/' . ACP_NAME);
        }
    }
}
