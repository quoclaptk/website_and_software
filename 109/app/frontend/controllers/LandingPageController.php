<?php namespace Modules\Frontend\Controllers;

use Modules\Models\LandingPage;
use Modules\Models\TmpLandingModule;
use Modules\Models\Subdomain;
use Phalcon\Mvc\Model\Query;
use Phalcon\Paginator\Adapter\QueryBuilder;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class LandingPageController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
    }

    public function detailAction($slug)
    {
        $this->view->setTemplateBefore('landingPage');
        $detail = LandingPage::findFirst([
            "conditions" => "subdomain_id = $this->_subdomain_id AND language_id = $this->languageId AND slug = '$slug' AND active = 'Y' AND deleted = 'N'"
        ]);

        $id = $detail->id;
        $hits = $detail->hits + 1;

        $phql   = "UPDATE Modules\Models\LandingPage SET hits = $hits WHERE id = $id";
        $result = $this->modelsManager->executeQuery($phql);
        if ($result->success() == false) {
            foreach ($result->getMessages() as $message) {
                echo $message->getMessage();
            }
        }

        $breadcrumb = "";

        $languageSlugs = [];
        if ($this->languageCode == 'vi') {
            $languageSlugs[$this->languageId] = $detail->slug;
            $dependLangs = LandingPage::findByDependId($detail->id);
            if (count($dependLangs) > 0) {
                foreach ($dependLangs as $dependLang) {
                    $languageSlugs[$dependLang->language_id] = $dependLang->slug;
                }
            }
        } else {
            $itemVi = LandingPage::findFirstById($detail->depend_id);
            $languageSlugs[$itemVi->language_id] = $itemVi->slug;
            $dependLangs = LandingPage::findByDependId($itemVi->id);
            if (count($dependLangs) > 0) {
                foreach ($dependLangs as $dependLang) {
                    $languageSlugs[$dependLang->language_id] = $dependLang->slug;
                }
            }
        }

        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langId = $tmpSubdomainLanguage->language_id;
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url($languageSlugs[$langId]) : $this->tag->site_url($langCode . '/' . $languageSlugs[$langId]);
            }
        }

        $conditions = ($this->languageCode == 'vi') ? " AND tmp.landing_page_id = $id" : " AND tmp.landing_page_id = $detail->depend_id";
        $tmpLandingModules = $this->modelsManager->createBuilder()
            ->columns(
                "tmp.id,
                tmp.landing_page_id,
                tmp.active,
                tmp.sort,
                mi.module_group_id,
                mi.parent_id,
                mi.name AS module_name,
                mi.id AS module_id,
                mi.module_group_id,
                mi.sort AS module_sort,
                mi.type"
            )
            ->addFrom("Modules\Models\TmpLandingModule", "tmp")
            ->join("Modules\Models\ModuleItem", "mi.id = tmp.module_item_id", "mi")
            ->where("mi.subdomain_id = ". $this->_subdomain_id ." AND mi.parent_id = 0". $conditions ."")
            ->orderBy("tmp.sort ASC, tmp.id DESC, mi.name ASC, mi.sort ASC, mi.type ASC, mi.id DESC")
            ->getQuery()
            ->execute();

        $this->view->languageUrls = $languageUrls;
        $this->view->title = (!empty($detail->title))? $detail->title : $detail->name;
        $this->view->keywords = (!empty($detail->keywords))? $detail->keywords : $detail->name;
        $this->view->description = (!empty($detail->description))? $detail->description : $detail->name;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->landingPage = $detail;
        $this->view->tmpLandingModules = $tmpLandingModules;
        $this->view->created_at = date('H:i:s d/m/Y', strtotime($detail->created_at));
        $this->view->pick($this->_getControllerName() . '/detail');
    }
}
