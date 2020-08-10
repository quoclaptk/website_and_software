<?php

namespace Modules\PhalconVn;

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
use Modules\Models\LayoutSubdomain;
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
use Modules\Models\SubdomainRating;
use Modules\Forms\DomainForm;
use Modules\Forms\SubdomainForm;
use Modules\PhalconVn\General;
use Modules\PhalconVn\MainGlobal;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class SubdomainService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function copyFolderOrigin($originFolder, $folder)
    {
        if (file_exists('assets/css/pages/' . $originFolder . '/style1.css')) {
            copy('assets/css/pages/' . $originFolder . '/style1.css', 'assets/css/pages/' . $folder . '/style1.css');
        }
        
        if (file_exists('assets/css/pages/' . $originFolder . '/page1.css')) {
            copy('assets/css/pages/' . $originFolder . '/page1.css', 'assets/css/pages/' . $folder . '/page1.css');
        }

        if (file_exists('assets/css/pages/' . $originFolder . '/style2.css')) {
            copy('assets/css/pages/' . $originFolder . '/style2.css', 'assets/css/pages/' . $folder . '/style2.css');
        }
        
        if (file_exists('assets/css/pages/' . $originFolder . '/page2.css')) {
            copy('assets/css/pages/' . $originFolder . '/page2.css', 'assets/css/pages/' . $folder . '/page2.css');
        }

        if (file_exists('assets/css/pages/' . $originFolder . '/style3.css')) {
            copy('assets/css/pages/' . $originFolder . '/style3.css', 'assets/css/pages/' . $folder . '/style3.css');
        }
        
        if (file_exists('assets/css/pages/' . $originFolder . '/page3.css')) {
            copy('assets/css/pages/' . $originFolder . '/page3.css', 'assets/css/pages/' . $folder . '/page3.css');
        }

        if (file_exists('assets/css/pages/' . $originFolder . '/style4.css')) {
            copy('assets/css/pages/' . $originFolder . '/style4.css', 'assets/css/pages/' . $folder . '/style4.css');
        }
        
        if (file_exists('assets/css/pages/' . $originFolder . '/page4.css')) {
            copy('assets/css/pages/' . $originFolder . '/page4.css', 'assets/css/pages/' . $folder . '/page4.css');
        }
        
        $this->mainGlobal->recurse_copy('files/default/' . $originFolder, 'files/default/' . $folder);
        $this->mainGlobal->recurse_copy('files/ads/' . $originFolder, 'files/ads/' . $folder);
        // $this->mainGlobal->recurse_copy('files/news/' . $originFolder , 'files/news/' . $folder);
        $this->mainGlobal->recurse_copy('files/category/' . $originFolder, 'files/category/' . $folder);
        // $this->mainGlobal->recurse_copy('files/product/' . $originFolder , 'files/product/' . $folder);
        $this->mainGlobal->recurse_copy('files/youtube/' . $originFolder, 'files/youtube/' . $folder);
        $this->mainGlobal->recurse_copy('files/icon/' . $originFolder, 'files/icon/' . $folder);

        /*if (is_dir('uploads/' . $originFolder)) {
            $this->mainGlobal->recurse_copy('uploads/' . $originFolder, 'uploads/' . $folder);
        }*/

        if (is_dir('uploads/' . $originFolder . '/category')) {
            $this->mainGlobal->recurse_copy('uploads/' . $originFolder . '/category', 'uploads/' . $folder . '/category');
        }
        
        if (is_dir('uploads/' . $originFolder . '/category')) {
            $this->mainGlobal->recurse_copy('uploads/' . $originFolder . '/post', 'uploads/' . $folder . '/post');
        }

        if (is_dir('uploads/' . $originFolder . '/article_home')) {
            $this->mainGlobal->recurse_copy('uploads/' . $originFolder . '/article_home', 'uploads/' . $folder . '/article_home');
        }

        if (is_dir('uploads/' . $originFolder . '/news_menu')) {
            $this->mainGlobal->recurse_copy('uploads/' . $originFolder . '/news_menu', 'uploads/' . $folder . '/news_menu');
        }

        if (is_dir('uploads/' . $originFolder . '/news_category')) {
            $this->mainGlobal->recurse_copy('uploads/' . $originFolder . '/news_category', 'uploads/' . $folder . '/news_category');
        }

        if (is_dir('uploads/' . $originFolder . '/news_type')) {
            $this->mainGlobal->recurse_copy('uploads/' . $originFolder . '/news_type', 'uploads/' . $folder . '/news_type');
        }

        if (is_dir('uploads/' . $originFolder . '/cdn/customer_photo')) {
            if (!is_dir('uploads/' . $folder . '/cdn')) {
                mkdir('uploads/' . $folder . '/cdn', 0777);
            }

            if (!is_dir('uploads/' . $folder . '/cdn/customer_photo')) {
                mkdir('uploads/' . $folder . '/cdn/customer_photo', 0777);
            }

            $this->mainGlobal->recurse_copy('uploads/' . $originFolder . '/cdn/customer_photo', 'uploads/' . $folder . '/cdn/customer_photo');
        }

        $this->mainGlobal->recurse_copy('bannerhtml/' . $originFolder, 'bannerhtml/' . $folder);
    }

    public function createDataOrigin(Subdomain $subdomainOrigin, Subdomain $subdomain)
    {
        $originId = $subdomainOrigin->id;
        $id = $subdomain->id;
        // $subdomain = Subdomain::findFirstById($originId);
        $this->createOriginWordItem($subdomainOrigin, $subdomain);
        $this->createOriginSetting($originId, $id);
        $this->createOriginModuleItem($originId, $id);
        $this->createOriginLayoutConfig($originId, $id);
        $this->createOriginPosts($originId, $id);
        $this->createOriginConfigItem($originId, $id);
        $this->createOriginBannerType($originId, $id);
        $this->createOriginBanner($originId, $id);
        $this->createOriginTmpBannerBannerType($originId, $id);
        $this->createOriginNewsMenu($originId, $id);
        $this->createOriginNews($subdomainOrigin, $subdomain);
        $this->createOriginTmpNewsMenu($originId, $id);
        $this->createOriginProduct($subdomainOrigin, $subdomain);
        $this->createOriginCategory($originId, $id);
        $this->createOriginProductDetail($originId, $id);
        $this->createOriginProductElement($originId, $id);
        $this->createOriginTmpProductCategory($originId, $id);
        $this->createOriginClip($originId, $id);
        $this->createOriginMenu($originId, $id);
        $this->createOriginCustomerComment($subdomainOrigin, $subdomain);
    }

    protected function createOriginWordItem($subdomainOrigin, $subdomain)
    {
        $messageFolder = $this->config->application->messages;
        $originId = $subdomainOrigin->id;
        $subdomainId = $subdomain->id;
        $originFolder = $subdomainOrigin->folder;
        $folder = $subdomain->folder;
        if (file_exists($messageFolder . 'subdomains/' . $originFolder . '/vi.json')) {
            if (!is_dir($messageFolder . 'subdomains/' . $folder)) {
                mkdir($messageFolder . 'subdomains/' . $folder, 0777);
            }

            copy($messageFolder . 'subdomains/' . $originFolder . '/vi.json', $messageFolder . 'subdomains/' . $folder . '/vi.json');
        } else {
            $wordItemCopies = WordItem::findBySubdomainId($originId);
            if (count($wordItemCopies) > 0) {
                foreach ($wordItemCopies->toArray() as $wordItemCopy) {
                    unset($wordItemCopy['id']);
                    unset($wordItemCopy['created_at']);
                    unset($wordItemCopy['modified_in']);
                    $wordItemCopy['subdomain_id'] = $subdomainId;
                    $wordItem = new WordItem();
                    $wordItem->assign($wordItemCopy);
                    $wordItem->save();
                }
            }
        }
    }

    protected function createOriginSetting($originId, $subdomainId)
    {
        // add setting
        $settingCopy = Setting::findFirstBySubdomainId($originId);
        if ($settingCopy) {
            $settingCopy = $settingCopy->toArray();
            $settingCopy['subdomain_id'] = $subdomainId;
            $settingCopy['analytics'] = null;
            $settingCopy['head_content'] = null;
            $settingCopy['body_content'] = null;
            unset($settingCopy['id']);
            $setting = new Setting();
            $setting->assign($settingCopy);

            $setting->save();
        }
    }

    protected function createOriginModuleItem($originId, $subdomainId)
    {
        $moduleItemCopies = ModuleItem::find([
            'conditions' => 'subdomain_id = '. $originId .' AND parent_id = 0'
        ]);
        if ($moduleItemCopies->count() > 0) {
            foreach ($moduleItemCopies->toArray() as $moduleItemCopy) {
                $tmpLayoutModuleCopies = TmpLayoutModule::find([
                    'conditions' => 'subdomain_id = '. $originId .' AND module_item_id = '. $moduleItemCopy['id'] .''
                ]);
                $moduleItemCopyChilds = ModuleItem::find([
                    'conditions' => 'subdomain_id = '. $originId .' AND parent_id = '. $moduleItemCopy['id'] .''
                ]);

                unset($moduleItemCopy['id']);
                unset($moduleItemCopy['created_at']);
                unset($moduleItemCopy['modified_in']);
                $moduleItemCopy['subdomain_id'] = $subdomainId;
                $moduleItem = new ModuleItem();
                $moduleItem->assign($moduleItemCopy);
                $moduleItem->save();

                if (count($tmpLayoutModuleCopies) > 0) {
                    foreach ($tmpLayoutModuleCopies->toArray() as $tmpLayoutModuleCopy) {
                        unset($tmpLayoutModuleCopy['id']);
                        $tmpLayoutModuleCopy['subdomain_id'] = $subdomainId;
                        $tmpLayoutModuleCopy['module_item_id'] = $moduleItem->id;
                        $tmpLayoutModule = new TmpLayoutModule();
                        $tmpLayoutModule->assign($tmpLayoutModuleCopy);
                        $tmpLayoutModule->save();
                    }
                }

                if ($moduleItemCopyChilds->count() > 0) {
                    foreach ($moduleItemCopyChilds->toArray() as $moduleItemCopyChild) {
                        $tmpLayoutModuleCopyChilds = TmpLayoutModule::find([
                            'conditions' => 'subdomain_id = '. $originId .' AND module_item_id = '. $moduleItemCopyChild['id'] .''
                        ]);

                        unset($moduleItemCopyChild['id']);
                        unset($moduleItemCopyChild['created_at']);
                        unset($moduleItemCopyChild['modified_in']);
                        $moduleItemCopyChild['parent_id'] = $moduleItem->id;
                        $moduleItemCopyChild['subdomain_id'] = $subdomainId;
                        $moduleItemChild = new ModuleItem();
                        $moduleItemChild->assign($moduleItemCopyChild);
                        $moduleItemChild->save();

                        if (count($tmpLayoutModuleCopyChilds) > 0) {
                            foreach ($tmpLayoutModuleCopyChilds->toArray() as $tmpLayoutModuleCopyChild) {
                                unset($tmpLayoutModuleCopyChild['id']);
                                $tmpLayoutModuleCopyChild['subdomain_id'] = $subdomainId;
                                $tmpLayoutModuleCopyChild['module_item_id'] = $moduleItemChild->id;
                                $tmpLayoutModuleChild = new TmpLayoutModule();
                                $tmpLayoutModuleChild->assign($tmpLayoutModuleCopyChild);
                                $tmpLayoutModuleChild->save();
                            }
                        }
                    }
                }
            }
        }
    }

    protected function createOriginLayoutConfig($originId, $subdomainId)
    {
        $layoutConfigOrigins = LayoutConfig::findBySubdomainId($originId);
        if (count($layoutConfigOrigins) > 0) {
            $layoutConfigOrigins = $layoutConfigOrigins->toArray();
            foreach ($layoutConfigOrigins as $layoutConfigOrigin) {
                $layoutConfigOrigin['subdomain_id'] = $subdomainId;
                unset($layoutConfigOrigin['id']);
                unset($layoutConfigOrigin['created_at']);
                unset($layoutConfigOrigin['modified_in']);

                $layoutConfig = new LayoutConfig();
                $layoutConfig->assign($layoutConfigOrigin);
                $layoutConfig->save();
            }
        }
    }

    protected function createOriginPosts($originId, $subdomainId)
    {
        $postsCopies = Posts::findBySubdomainId($originId);
        if (count($postsCopies) > 0) {
            foreach ($postsCopies as $postsCopy) {
                $moduleItemCopy = ModuleItem::findFirstById($postsCopy->module_item_id);
                if ($moduleItemCopy) {
                    $moduleItem = ModuleItem::findFirst([
                        'conditions' => 'subdomain_id = '. $subdomainId .' AND name = "'. $moduleItemCopy->name .'" AND type = "post" AND parent_id = 0'
                    ]);
                    
                    $posts = new Posts();

                    $data = $postsCopy->toArray();
                    $data['subdomain_id'] = $subdomainId;
                    $data['module_item_id'] = $moduleItem->id;
                    unset($data['id']);
                    unset($data['created_at']);
                    unset($data['modified_in']);

                    $posts->assign($data);
                    $posts->save();
                }
            }

            $moduleItemChildPosts = ModuleItem::find([
                'conditions' => 'subdomain_id = '. $subdomainId .' AND type = "post" AND parent_id != 0',
                'order' => 'sort ASC, id DESC'
            ]);

            $postNews = Posts::find([
                'conditions' => 'subdomain_id = '. $subdomainId .'',
                'order' => 'sort ASC, id DESC'
            ]);

            if (count($moduleItemChildPosts) > 0 && count($postNews) > 0) {
                foreach ($moduleItemChildPosts as $key => $moduleItemChildPost) {
                    if (isset($postNews[$key])) {
                        $moduleItemChildPost->type_id = $postNews[$key]->id;
                        $moduleItemChildPost->save();
                    }
                }
            }
        }
    }

    protected function createOriginConfigItem($originId, $subdomainId)
    {
        $configItemCopies = ConfigItem::findBySubdomainId($originId);
        if (count($configItemCopies) > 0) {
            foreach ($configItemCopies as $configItemCopy) {
                $configItem = new ConfigItem();
                $data = $configItemCopy->toArray();
                if ($data['field'] == '_cf_text_fanpage_id') {
                    unset($data['value']);
                }

                $data['subdomain_id'] = $subdomainId;
                unset($data['id']);
                $configItem->assign($data);
                $configItem->save();
            }
        }
    }

    protected function createOriginBannerType($originId, $subdomainId)
    {
        $bannerTypeCopies = BannerType::findBySubdomainId($originId);
        if (count($bannerTypeCopies) > 0) {
            foreach ($bannerTypeCopies as $bannerTypeCopy) {
                $moduleItem = ModuleItem::findFirst([
                    'conditions' => 'subdomain_id = '. $subdomainId .' AND name = "'. $bannerTypeCopy->name .'" AND type = "banner"'
                ]);
                $bannerType = new BannerType();

                $bannerType->assign([
                    'subdomain_id' => $subdomainId,
                    'module_item_id' => $moduleItem->id,
                    'name' => $bannerTypeCopy->name,
                    'type' => $bannerTypeCopy->type,
                    'sort' => $bannerTypeCopy->sort,
                    'active' => $bannerTypeCopy->active
                ]);

                $bannerType->save();
            }
        }
    }

    protected function createOriginBanner($originId, $subdomainId)
    {
        $bannerCopies = Banner::findBySubdomainId($originId);
        if (count($bannerCopies) > 0) {
            $bannerCopies = $bannerCopies->toArray();
            foreach ($bannerCopies as $bannerCopy) {
                unset($bannerCopy['id']);
                unset($bannerCopy['created_at']);
                unset($bannerCopy['modified_in']);
                $bannerCopy['subdomain_id'] = $subdomainId;
                $banner = new Banner();
                $banner->assign($bannerCopy);
                $banner->save();
            }
        }
    }

    protected function createOriginTmpBannerBannerType($originId, $subdomainId)
    {
        $bannerTypeCopies = BannerType::find([
            'conditions' => 'subdomain_id = '. $originId .''
        ]);

        if (count($bannerTypeCopies) > 0) {
            foreach ($bannerTypeCopies as $bannerTypeCopy) {
                $bannerType = BannerType::findFirst([
                    'conditions' => 'subdomain_id = '. $subdomainId .' AND name = "'. $bannerTypeCopy->name .'"'
                ]);
                if ($bannerType) {
                    $tmpBannerBannerTypeCopies = TmpBannerBannerType::findByBannerTypeId($bannerTypeCopy->id);
                    if (count($tmpBannerBannerTypeCopies) > 0) {
                        foreach ($tmpBannerBannerTypeCopies as $tmpBannerBannerTypeCopy) {
                            $bannerCopy = Banner::findFirstById($tmpBannerBannerTypeCopy->banner_id);
                            if ($bannerCopy) {
                                $banner = Banner::findFirst([
                                    'conditions' => 'subdomain_id = '. $subdomainId .' AND photo = "'. $bannerCopy->photo .'"'
                                ]);
                                $this->modelsManager->executeQuery("INSERT INTO Modules\Models\TmpBannerBannerType (banner_type_id, banner_id, subdomain_id) "
                                    . "VALUES ($bannerType->id, $banner->id, $subdomainId)");
                            }
                        }
                    }
                }
            }
        }
    }

    protected function createOriginNewsMenu($originId, $subdomainId)
    {
        $this->recursiveCopyNewsMenu($originId, $subdomainId);
    }

    protected function recursiveCopyNewsMenu($originId, $subdomainId, $parent_origin_id = 0, $parent_id = 0)
    {
        $newsMenuCopies = NewsMenu::find([
            'conditions' => 'Modules\Models\NewsMenu.subdomain_id = '. $originId .' AND parent_id = '. $parent_origin_id .''
        ]);

        if (count($newsMenuCopies) > 0) {
            foreach ($newsMenuCopies as $newsMenuCopy) {
                $data = $newsMenuCopy->toArray();
                $data['subdomain_id'] = $subdomainId;
                $data['parent_id'] = $parent_id;
                unset($data['id']);

                $newsMenu = new NewsMenu();
                $newsMenu->assign($data);
                if ($newsMenu->save()) {
                    $queryId = $newsMenu->id;
                    $this->recursiveCopyNewsMenu($originId, $subdomainId, $newsMenuCopy->id, $queryId);
                }
            }
        }
    }

    protected function createOriginNews(Subdomain $subdomainOrigin, Subdomain $subdomain)
    {
        $originFolder = $subdomainOrigin->folder;
        $folder = $subdomain->folder;
        $newsCopies = News::find([
            'conditions' => 'subdomain_id = '. $subdomainOrigin->id .'',
            'order' => 'id DESC',
            'limit' => 20
        ]);

        if (count($newsCopies) > 0) {
            foreach ($newsCopies as $newsCopy) {
                $data = $newsCopy->toArray();
                $data['subdomain_id'] = $subdomain->id;
                $data['hits'] = 0;
                unset($data['id']);
                $news = new News();
                $news->assign($data);
                if ($news->save()) {
                    if (is_dir('files/news/' . $originFolder . '/' . $newsCopy->folder)) {
                        if (!is_dir("files/news/" . $folder . "/" . $newsCopy->folder)) {
                            mkdir("files/news/" . $folder . "/" . $newsCopy->folder, 0777);
                        }

                        $this->mainGlobal->recurse_copy('files/news/' . $originFolder . '/' . $newsCopy->folder, 'files/news/' . $folder . '/' . $newsCopy->folder);
                    }

                    if (is_dir('uploads/' . $originFolder . '/news/' . $newsCopy->row_id)) {
                        $this->mainGlobal->recurse_copy('uploads/' . $originFolder . '/news/' . $newsCopy->row_id, 'uploads/' . $folder . '/news/' . $newsCopy->row_id);
                    }
                }
            }
        }
    }

    protected function createOriginTmpNewsMenu($originId, $subdomainId)
    {
        $newsMenuCopies = NewsMenu::findBySubdomainId($originId);

        if (count($newsMenuCopies) > 0) {
            foreach ($newsMenuCopies as $newsMenuCopy) {
                $newsMenu = NewsMenu::findFirst([
                    'conditions' => 'subdomain_id = '. $subdomainId .' AND slug = "'. $newsMenuCopy->slug .'"'
                ]);
                if ($newsMenu) {
                    $tmpNewsNewsMenuCopies = TmpNewsNewsMenu::findByNewsMenuId($newsMenuCopy->id);
                    if (count($tmpNewsNewsMenuCopies) > 0) {
                        foreach ($tmpNewsNewsMenuCopies as $tmpNewsNewsMenuCopy) {
                            $newsCopy = News::findFirstById($tmpNewsNewsMenuCopy->news_id);
                            if ($newsCopy) {
                                $news = News::findFirst([
                                    'conditions' => 'subdomain_id = '. $subdomainId .' AND slug = "'. $newsCopy->slug .'"'
                                ]);
                                if ($news) {
                                    // $this->modelsManager->executeQuery("INSERT INTO Modules\Models\TmpNewsNewsMenu (news_menu_id, news_id) "
                                    //    . "VALUES ($newsMenu->id, $news->id)");
                                    $tmpNewsMenu = new TmpNewsNewsMenu();
                                    $tmpNewsMenu->assign([
                                        'subdomain_id' => $subdomainId,
                                        'news_menu_id' => $newsMenu->id,
                                        'news_id' => $news->id
                                    ]);
                                    if (!$tmpNewsMenu->save()) {
                                        foreach ($tmpNewsMenu->getMessages() as $message) {
                                            $this->flashSession->error($message);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function createOriginProduct(Subdomain $subdomainOrigin, Subdomain $subdomain)
    {
        $originFolder = $subdomainOrigin->folder;
        $folder = $subdomain->folder;
        $productCopies = Product::find([
            'conditions' => 'subdomain_id = '. $subdomainOrigin->id .'',
            'order' => 'hot DESC, new DESC, id DESC',
            'limit' => 20
        ]);
        if (count($productCopies) > 0) {
            foreach ($productCopies as $productCopy) {
                $data = $productCopy->toArray();
                $data['subdomain_id'] = $subdomain->id;
                $data['hits'] = 0;
                unset($data['id']);
                $product = new Product();
                $product->assign($data);
                if ($product->save()) {
                    if (is_dir('files/product/' . $originFolder . '/' . $productCopy->folder)) {
                        if (!is_dir("files/product/" . $folder . "/" . $productCopy->folder)) {
                            mkdir("files/product/" . $folder . "/" . $productCopy->folder, 0777);
                        }

                        $this->mainGlobal->recurse_copy('files/product/' . $originFolder . '/' . $productCopy->folder, 'files/product/' . $folder . '/' . $productCopy->folder);
                    }

                    if (is_dir('uploads/' . $originFolder . '/product/' . $productCopy->row_id)) {
                        if (!is_dir("uploads/" . $folder . "/" . $productCopy->folder . "/" .  $productCopy->row_id)) {
                            mkdir("uploads/" . $folder . "/" . $productCopy->folder . "/" .  $productCopy->row_id, 0777);
                        }

                        $this->mainGlobal->recurse_copy('uploads/' . $originFolder . '/product/' . $productCopy->row_id, 'uploads/' . $folder . '/product/' . $productCopy->row_id);
                    }
                } else {
                    foreach ($product->getMessages() as $message) {
                        $this->flashSession->error($message);
                    }
                }
            }
        }
    }

    protected function createOriginCategory($originId, $subdomainId)
    {
        $this->recursiveCopyCategory($originId, $subdomainId);
    }

    protected function recursiveCopyCategory($originId, $subdomainId, $parent_origin_id = 0, $parent_id = 0)
    {
        $categoryCopies = Category::find([
            'conditions' => 'Modules\Models\Category.subdomain_id = '. $originId .' AND parent_id = '. $parent_origin_id .''
        ]);

        if (count($categoryCopies) > 0) {
            foreach ($categoryCopies as $categoryCopy) {
                $data = $categoryCopy->toArray();
                $data['subdomain_id'] = $subdomainId;
                $data['parent_id'] = $parent_id;
                unset($data['id']);
                $category = new Category();
                $category->assign($data);
                if ($category->save()) {
                    $queryId = $category->id;
                    $this->recursiveCopyCategory($originId, $subdomainId, $categoryCopy->id, $queryId);
                }
            }
        }
    }

    protected function createOriginProductDetail($originId, $subdomainId)
    {
        $productDetailCopies = ProductDetail::findBySubdomainId($originId);
        if (count($productDetailCopies) > 0) {
            foreach ($productDetailCopies as $productDetailCopy) {
                $productContentCopies = ProductContent::findByProductDetailId($productDetailCopy->id);
                $productDetailCopyData = $productDetailCopy->toArray();
                $productDetailCopyData['subdomain_id'] = $subdomainId;
                unset($productDetailCopyData['id']);
                $productDetail = new ProductDetail();
                $productDetail->assign($productDetailCopyData);
                if ($productDetail->save()) {
                    $productDetailId = $productDetail->id;
                    if (count($productContentCopies) > 0) {
                        foreach ($productContentCopies as $productContentCopy) {
                            $productCopy = Product::findFirstById($productContentCopy->product_id);
                            if ($productCopy) {
                                $product = Product::findFirst([
                                    'conditions' => 'subdomain_id = '. $subdomainId .' AND slug = "'. $productCopy->slug .'"'
                                ]);
                                if ($product) {
                                    $productContentCopyData = $productContentCopy->toArray();
                                    $productContentCopyData['subdomain_id'] = $subdomainId;
                                    $productContentCopyData['product_id'] = $product->id;
                                    $productContentCopyData['product_detail_id'] = $productDetailId;
                                    unset($productContentCopyData['id']);

                                    $productContent = new ProductContent();
                                    $productContent->assign($productContentCopyData);
                                    $productContent->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function createOriginProductElement($originId, $subdomainId)
    {
        $productElementCopies = ProductElement::findBySubdomainId($originId);
        if (count($productElementCopies) > 0) {
            foreach ($productElementCopies as $productElementCopy) {
                $productElementCopyData = $productElementCopy->toArray();
                $productElementCopyData['subdomain_id'] = $subdomainId;
                unset($productElementCopyData['id']);
                $productElement = new ProductElement();
                $productElement->assign($productElementCopyData);
                if ($productElement->save()) {
                    // save product element detail
                    $productElementDetailCopies = ProductElementDetail::findByProductElementId($productElementCopy->id);
                    if (count($productElementDetailCopies) > 0) {
                        foreach ($productElementDetailCopies as $productElementDetailCopy) {
                            $productElementDetailCopyData = $productElementDetailCopy->toArray();
                            $productElementDetailCopyData['subdomain_id'] = $subdomainId;
                            $productElementDetailCopyData['product_element_id'] = $productElement->id;
                            unset($productElementDetailCopyData['id']);
                            $productElementDetail = new ProductElementDetail();
                            $productElementDetail->assign($productElementDetailCopyData);
                            if ($productElementDetail->save()) {
                                $productElementDetailId = $productElementDetail->id;
                                $tmpProductProductElementDetailCopies = TmpProductProductElementDetail::findByProductElementDetailId($productElementDetailCopy->id);
                                if (count($tmpProductProductElementDetailCopies) > 0) {
                                    foreach ($tmpProductProductElementDetailCopies as $tmpProductProductElementDetailCopy) {
                                        $productCopy = Product::findFirstById($tmpProductProductElementDetailCopy->product_id);
                                        if ($productCopy) {
                                            $product = Product::findFirst([
                                                'conditions' => 'subdomain_id = '. $subdomainId .' AND slug = "'. $productCopy->slug .'"'
                                            ]);
                                            if ($product) {
                                                $tmpProductProductElementDetail = new TmpProductProductElementDetail();
                                                $tmpProductProductElementDetail->assign([
                                                    'subdomain_id' => $subdomainId,
                                                    'product_id' => $product->id,
                                                    'product_element_detail_id' => $productElementDetailId,
                                                ]);
                                                $tmpProductProductElementDetail->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function createOriginTmpProductCategory($originId, $subdomainId)
    {
        $categoryCopies = Category::find([
            'conditions' => 'subdomain_id = '. $originId .''
        ]);

        if (count($categoryCopies) > 0) {
            foreach ($categoryCopies as $categoryCopy) {
                $category = Category::findFirst([
                    'conditions' => 'subdomain_id = '. $subdomainId .' AND slug = "'. $categoryCopy->slug .'"'
                ]);
                if ($category) {
                    $tmpProductCategoryCopies = TmpProductCategory::findByCategoryId($categoryCopy->id);
                    if (count($tmpProductCategoryCopies) > 0) {
                        foreach ($tmpProductCategoryCopies as $tmpProductCategoryCopy) {
                            $productCopy = Product::findFirstById($tmpProductCategoryCopy->product_id);
                            if ($productCopy) {
                                $product = Product::findFirst([
                                    'conditions' => 'subdomain_id = '. $subdomainId .' AND slug = "'. $productCopy->slug .'"'
                                ]);

                                if ($product) {
                                    $this->modelsManager->executeQuery("INSERT INTO Modules\Models\TmpProductCategory (category_id, product_id, subdomain_id) "
                                    . "VALUES ($category->id, $product->id, $subdomainId)");
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function createOriginClip($originId, $subdomainId)
    {
        $clipCopies = Clip::findBySubdomainId($originId);
        if (count($clipCopies) > 0) {
            foreach ($clipCopies as $clipCopy) {
                $clip = new Clip();
                $data = array(
                    'subdomain_id' => $subdomainId,
                    'name' => $clipCopy->name,
                    'slug' => $clipCopy->slug,
                    'code' => $clipCopy->code,
                    'photo' => $clipCopy->photo,
                    'title' => $clipCopy->title,
                    'keywords' => $clipCopy->keywords,
                    'description' => $clipCopy->description,
                    'summary' => $clipCopy->summary,
                    'sort' => $clipCopy->sort,
                    'active' => $clipCopy->active,
                    'folder' => $clipCopy->folder,
                    'deleted' => $clipCopy->deleted,
                );
                $clip->assign($data);
                $clip->save();
            }
        }
    }

    protected function createOriginMenu($originId, $subdomainId)
    {
        $menuCopys = Menu::findBySubdomainId($originId);
        if (count($menuCopys) > 0) {
            foreach ($menuCopys as $menuCopy) {
                $moduleItem = ModuleItem::findFirst([
                    'conditions' => 'subdomain_id = '. $subdomainId .' AND name = "'. $menuCopy->name .'" AND type = "menu"'
                ]);
                $menu = new Menu();

                $data = [
                    'subdomain_id' => $subdomainId,
                    'module_item_id' => ($moduleItem) ? $moduleItem->id : 0,
                    'name' => $menuCopy->name,
                    'sort' => $menuCopy->sort,
                    'style' => $menuCopy->style,
                    'main' => $menuCopy->main,
                    'active' => $menuCopy->active
                ];

                $menu->assign($data);

                if ($menu->save()) {
                    $id = $menu->id;

                    $menuItemCopys = MenuItem::findByMenuId($menuCopy->id);

                    if (count($menuItemCopys) > 0) {
                        foreach ($menuItemCopys as $menuItemCopy) {
                            switch ($menuItemCopy->module_name) {
                                case 'category':
                                    $category = Category::findFirst([
                                        'conditions' => 'subdomain_id = '. $subdomainId .' AND slug = "'. $menuItemCopy->url .'"'
                                    ]);
                                    $module_id = ($category) ? $category->id : 0;
                                    break;

                                case 'news_menu':
                                    $newsMenu = NewsMenu::findFirst([
                                        'conditions' => 'subdomain_id = '. $subdomainId .' AND slug = "'. $menuItemCopy->url .'"'
                                    ]);
                                    $module_id = ($newsMenu) ? $newsMenu->id : 0;
                                    break;

                                case 'news_type':
                                    $newsType = NewsType::findFirst([
                                        'conditions' => 'subdomain_id = '. $subdomainId .' AND slug = "'. $menuItemCopy->url .'"'
                                    ]);
                                    $module_id = ($newsType) ? $newsType->id : 0;
                                    break;
                                
                                default:
                                    $module_id = 0;
                                    break;
                            }
                            $menuItem = new MenuItem();
                            $menuItem->assign([
                                'subdomain_id' => $subdomainId,
                                'menu_id' => $id,
                                'parent_id' => $menuItemCopy->parent_id,
                                'module_id' => $module_id,
                                'level' => $menuItemCopy->level,
                                'module_name' => $menuItemCopy->module_name,
                                'name' => $menuItemCopy->name,
                                'url' => $menuItemCopy->url,
                                'active' => $menuItemCopy->active,
                                'font_class' => $menuItemCopy->font_class,
                                'photo' => $menuItemCopy->photo,
                                'icon_type' => $menuItemCopy->icon_type,
                                'sort' => $menuItemCopy->sort
                            ]);

                            $menuItem->save();
                        }
                    }
                }
            }
        }
    }

    protected function createOriginCustomerComment($subdomainOrigin, $subdomain)
    {
        $originId = $subdomainOrigin->id;
        $subdomainId = $subdomain->id;
        $folderOrgin = $subdomainOrigin->folder;
        $folder = $subdomain->folder;
        $customerCommentCopies = CustomerComment::findBySubdomainId($originId);
        if (count($customerCommentCopies) > 0) {
            $customerCommentCopies = $customerCommentCopies->toArray();
            foreach ($customerCommentCopies as $customerCommentCopy) {
                $customerComment = new CustomerComment();
                $customerCommentCopy['subdomain_id'] = $subdomainId;
                if (!empty($customerCommentCopy['photo'])) {
                    $customerCommentCopy['photo'] = str_replace($folderOrgin . '/', $folder . '/', $customerCommentCopy['photo']);
                }
                unset($customerCommentCopy['id']);
                unset($customerCommentCopy['created_at']);
                $customerComment->assign($customerCommentCopy);
                $customerComment->save();
            }
        }
    }

    protected function createBannerHtml($subdomainId, $folder)
    {
        $query = $this->modelsManager->executeQuery("INSERT INTO Modules\Models\BannerHtml (subdomain_id) "
            . "VALUES ($subdomainId)");

        if ($query->success()) {
            $bannerHtmlId = $query->getModel()->id;
            $setting = Setting::findFirstBySubdomainId($subdomainId);
            $setting->assign(['banner_html_id' => $bannerHtmlId]);
            $setting->save();

            if (!is_dir("bannerhtml/" . $folder . "/" . $bannerHtmlId)) {
                mkdir("bannerhtml/" . $folder . "/" . $bannerHtmlId, 0777);
            }

            copy('assets/source/bannerhtml/css/style3.css', 'bannerhtml/'  . $folder . '/' . $bannerHtmlId . '/style.css');
        }
    }

    public function getSubdomainElms()
    {
        $subdomains = $this->modelsManager->createBuilder()
            ->addFrom("Modules\Models\Subdomain", "s")
            ->columns("s.id, s.name, s.special, s.duplicate, d.name AS domain_name, SUM(sr.rate) AS sum_rate")
            ->leftJoin("Modules\Models\Domain", "d.subdomain_id =  s.id", "d")
            ->leftJoin("Modules\Models\SubdomainRating", "sr.subdomain_id =  s.id", "sr")
            ->where("s.name !='@'")
            ->groupBy("s.id")
            ->orderBy("sum_rate DESC, s.special DESC, s.active DESC, s.id DESC")
            ->getQuery()
            ->execute();

        return $subdomains;
    }

    public function getSubdomainList()
    {
        $subdomains = Subdomain::find([
            "conditions" => "name !='@'",
            "order" => "special DESC, active DESC, id DESC"
        ]);

        if (count($subdomains) > 0) {
            $active = [];
            $list = [];
            $all = [];
            foreach ($subdomains as $subdomain) {
                $item = (object) $subdomain->toArray();
                if (count($subdomain->domain) > 0) {
                    $item->domains = $subdomain->domain->toArray();
                }

                $rate = 0;
                if (count($subdomain->subdomainRating) > 0) {
                    $rate = SubdomainRating::sum(
                        [
                            'column'     => 'rate',
                            'conditions' => "subdomain_id = $subdomain->id",
                        ]
                    );
                }

                $item->rate = $rate;
                
                $all[] = $item;
                if ($subdomain->active == 'Y' && $subdomain->suspended == 'N' && $subdomain->closed == 'N' && $subdomain->deleted == 'N') {
                    $active[] = (object) $item;
                }
                
                if ($subdomain->suspended == 'N' && $subdomain->closed == 'N' && $subdomain->deleted == 'N') {
                    $list[] = (object) $item;
                }
            }

            usort($all, function ($a, $b) {
                return $b->rate <=> $a->rate;
            });

            usort($active, function ($a, $b) {
                return $b->rate <=> $a->rate;
            });

            usort($list, function ($a, $b) {
                return $b->rate <=> $a->rate;
            });

            $result = compact('all', 'active', 'list');
        }

        return $result;
    }

    public function getAllSubdomains($page = 1)
    {
        $all = $this->modelsManager->createBuilder()
            ->addFrom("Modules\Models\Subdomain", "s")
            ->columns("s.id, s.name, s.special, s.duplicate, s.create_id, s.display, d.name AS domain_name, SUM(sr.rate) AS sum_rate")
            ->leftJoin("Modules\Models\Domain", "d.subdomain_id =  s.id", "d")
            ->leftJoin("Modules\Models\SubdomainRating", "sr.subdomain_id =  s.id", "sr")
            ->where("s.name !='@' AND s.suspended = 'N' AND s.closed = 'N' AND s.deleted = 'N'")
            ->groupBy("s.id")
            ->orderBy("sum_rate DESC, s.special DESC, s.active DESC, s.id DESC");

        $active = $this->modelsManager->createBuilder()
            ->addFrom("Modules\Models\Subdomain", "s")
            ->columns("s.id, s.name, s.special, s.duplicate, s.create_id, s.display, d.name AS domain_name, SUM(sr.rate) AS sum_rate")
            ->leftJoin("Modules\Models\Domain", "d.subdomain_id =  s.id", "d")
            ->leftJoin("Modules\Models\SubdomainRating", "sr.subdomain_id =  s.id", "sr")
            ->where("s.name !='@' AND s.active = 'Y' AND s.suspended = 'N' AND s.closed = 'N' AND s.deleted = 'N'")
            ->groupBy("s.id")
            ->orderBy("sum_rate DESC, s.special DESC, s.active DESC, s.id DESC");

        /*$list = $this->modelsManager->createBuilder()
            ->addFrom("Modules\Models\Subdomain", "s")
            ->columns("s.id, s.name, s.special, s.duplicate, d.name AS domain_name, SUM(sr.rate) AS sum_rate")
            ->leftJoin("Modules\Models\Domain", "d.subdomain_id =  s.id", "d")
            ->leftJoin("Modules\Models\SubdomainRating", "sr.subdomain_id =  s.id", "sr")
            ->where("s.name != '@' AND s.suspended = 'N' AND s.closed = 'N' AND s.deleted = 'N'")
            ->groupBy("s.id")
            ->orderBy("sum_rate DESC, s.special DESC, s.active DESC, s.id DESC");*/

        $all = $this->pagination_service->queryBuilder($all, 200, $page);
        $active = $this->pagination_service->queryBuilder($active, 200, $page);
        // $list = $this->pagination_service->queryBuilder($list, 200, $page);

        $result = compact('all', 'active');

        return $result;
    }

    public function searchAdmin($keyword)
    {
        $sql = "SELECT s.id, s.name, s.special, s.duplicate, d.name AS domain_name, sr.rate AS sum_rate
                FROM subdomain s 
                LEFT JOIN subdomain_rating sr ON s.id = sr.subdomain_id
                LEFT JOIN domain d ON s.id = d.subdomain_id 
                WHERE s.name != '@' AND (s.name LIKE '%". $keyword ."%' OR d.name LIKE '%". $keyword ."%')
                GROUP BY s.id
                ORDER BY sr.rate DESC, s.special DESC, s.active DESC, s.id DESC";

        // Base model
        $sub = new Subdomain();

        // Execute the query
        $result = new Resultset(
            null,
            $sub,
            $sub->getReadConnection()->query($sql)
        );

        return $result;
    }

    public function search($keyword)
    {
        return $this->modelsManager->createBuilder()
            ->addFrom("Modules\Models\Subdomain", "s")
            ->columns("s.*, d.name AS domain_name")
            ->leftJoin("Modules\Models\Domain", "d.subdomain_id =  s.id", "d")
            ->where("(s.name LIKE '%". $keyword ."%' OR d.name LIKE '%". $keyword ."%') AND s.name != '@'")
            ->groupBy("s.id")
            ->orderBy("s.special DESC, s.active DESC, s.id DESC")
            ->getQuery()
            ->execute();
    }

    /**
     * Search Fulltext
     */
    public function fulltextSearch($keyword)
    {
        $sql = 'SELECT s.id, s.name, s.special, s.duplicate, d.name AS domain_name, sr.rate AS sum_rate
                FROM subdomain s 
                LEFT JOIN subdomain_rating sr ON s.id = sr.subdomain_id
                LEFT JOIN domain d ON s.id = d.subdomain_id 
                LEFT JOIN menu_item mi ON s.id = mi.subdomain_id 
                LEFT JOIN category c ON s.id = c.subdomain_id 
                WHERE s.name != "" AND (MATCH (s.name) AGAINST ("'. $keyword .'" IN BOOLEAN MODE) OR MATCH (d.name) AGAINST ("'. $keyword .'" IN BOOLEAN MODE) OR MATCH (mi.name) AGAINST ("'. $keyword .'" IN BOOLEAN MODE) OR MATCH (mi.url) AGAINST ("'. $keyword .'" IN BOOLEAN MODE) OR MATCH (c.name) AGAINST ("'. $keyword .'" IN BOOLEAN MODE) OR MATCH (c.slug) AGAINST ("'. $keyword .'" IN BOOLEAN MODE))
                GROUP BY s.id
                ORDER BY sr.rate DESC, s.special DESC, s.active DESC, s.id DESC';

        // Base model
        $sub = new Subdomain();

        // Execute the query
        $result = new Resultset(
            null,
            $sub,
            $sub->getReadConnection()->query($sql)
        );

        return $result;
    }

    public function getSumRate($subdomainId)
    {
        $sumRate = SubdomainRating::sum(
            [
                'column'     => 'rate',
                'conditions' => "subdomain_id = $subdomainId",
            ]
        );

        return $sumRate;
    }

    /**
     * Get subdomain create
     *
     * @param $createId
     * @return string|null
     */
    public function getSubdomainCreate($createId)
    {
        $domainName = null;
        $user = Users::findFirstById($createId);
        if ($user) {
            $domainName = ($user->subdomain->name != '@') ? $user->subdomain->name . '.' . ROOT_DOMAIN : ROOT_DOMAIN;
        }

        return $domainName;
    }

    
}
