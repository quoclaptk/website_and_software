<?php namespace Modules\Acl;

use Phalcon\Mvc\User\Component;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl\Resource as AclResource;
//Eduapps\Models\Users,
use Modules\Models\Profiles;

/**
 * Eduapps\Acl\Acl
 *
 *
 */
class Acl extends Component
{
    private $_acl;


    private $_privateResources = array(
            'users' => array('index', 'search', 'edit', 'create', 'delete', 'changePassword','deletemulty','transfer', 'createsubdomain'),
            'profiles' => array('index', 'search', 'edit', 'create', 'show', 'hide', 'delete', 'showmulty', 'hidemulty', 'deletemulty'),
            'permissions' => array('index','search','edit'),
            'category'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty', 'showhot', 'hidehot', 'showlist', 'hidelist', 'showmenu', 'hidemenu', 'showpicture', 'hidepicture'),
            'category_video'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty', 'showhot', 'hidehot'),
            'news'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty', 'showhot', 'hidehot'),
            'video'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty'),
            'clip'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty'),
            'url_config'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty', 'createnews', 'loadnews', 'getimgreplace', 'loadcontent'),
            'banner'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty'),
            'text_link'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty'),
            'tags'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty'),
            'leagues'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty'),
            'channel'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty'),
            'streaming'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty', 'showhot', 'hidehot', 'channel', 'auto', 'creatStreamMulty'),
            'channel_group'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty'),
            'tv'=> array('index','update','create','delete','show', 'hide', 'showmulty', 'hidemulty', 'deletemulty'),
            'setting'=> array('index'),
            'orders'=> array('index'),
            'product'=> array('index'),
            'news_type'=> array('index'),
            'news_menu'=> array('index'),
            'news_category'=> array('news_category'),
            'menu'=> array('index'),
            'subdomain'=> array('index'),
            'posts'=> array('index'),
            'module_item'=> array('index'),
            'word_item'=> array('index'),
            'module_group'=> array('index'),
            'config_kernel'=> array('index'),
            'config_core'=> array('index'),
            'config_item'=> array('index'),
    );
        
        
    private $_resourceDescriptions = array(
        'users' => 'Tài khoản quản trị',
        'profiles' => 'Nhóm quản trị',
        'permissions' => 'Phân quyền',
        'category' => 'Danh mục tin tức',
        'news' => 'Tin tức',
        'category_video' => 'Danh mục Video',
        'video' => 'Video',
        'clip' => 'Video Youtube',
        'url_config' =>'Lấy tin tự động',
        'banner' => 'Banner',
        'text_link' => 'Text Link',
        'tags' => 'Tags',
        'leagues' => 'Giải đấu',
        'channel' => 'Kênh',
        'streaming' => 'Trực tiếp',
        'channel_group' => 'Nhóm kênh TV',
        'tv' => 'TV',
        'setting' => 'Cấu hình site'
    );

    private $_actionDescriptions = array(
        'index' => 'Xem',
        'search' => 'Tìm kiếm',
        'create' => 'Thêm mới',
        'createnews' => 'Thêm tin tự động',
        'edit' => 'Chỉnh sửa',
        'update' => 'Cập nhật',
        'delete' => 'Xóa',
        'transfe' =>'Transfe',
        'createsubdomain' =>'Tạo web',
        'changePassword' => 'Thay đổi mật khẩu',
        'deletemulty' => 'Xóa nhiều mục',
        'show' => 'Hiển thị',
        'hide' => 'Ẩn',
        'showmulty' => 'Hiển thị nhiều mục',
        'hidemulty' => 'Ẩn nhiều mục',
        'showhot' => 'Show Hot',
        'hidehot' => 'Hide Hot',
        'showlist' => 'Show List',
        'hidelist' => 'Hide List',
        'showmenu' => 'Show Menu',
        'hidemenu' => 'Hide Menu',
        'showpicture' => 'Show Picture',
        'hidepicture' => 'Hide Picture',
        'loadnews' => 'Load tin tự động',
        'getimgreplace' => 'Load hình ảnh gốc',
        'loadcontent' => 'Load nội dung',
        'channel' => 'Chọn kênh',
        'auto' => 'Lấy danh sách kênh tự động',
        'creatStreamMulty' => 'Up nhiều kênh',
    );

    /**
     * Checks if a controller is private or not
     *
     * @param string $controllerName
     * @return boolean
     */
    public function isPrivate($controllerName)
    {
        return isset($this->_privateResources[$controllerName]);
    }

    /**
     * Checks if the current profile is allowed to access a resource
     *
     * @param string $profile
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isAllowed($profile, $controller, $action)
    {
        return $this->getAcl()->isAllowed($profile, $controller, $action);
    }

    /**
     * Returns the ACL list
     *
     * @return Phalcon\Acl\Adapter\Memory
     */
    public function getAcl()
    {
        $this->_acl = $this->rebuild();


        return $this->_acl;
    }

    /**
     * Returns the permissions assigned to a profile
     *
     * @param Profiles $profile
     * @return array
     */
    public function getPermissions(Profiles $profile)
    {
        $permissions = array();
        foreach ($profile->getPermissions() as $permission) {
            $permissions[$permission->resource . '.' . $permission->action] = true;
        }
        return $permissions;
    }

    /**
     * Returns all the resoruces and their actions available in the application
     *
     * @return array
     */
    public function getResources()
    {
        return $this->_privateResources;
    }

    /**
     * Returns the action description according to its simplified name
     *
     * @param string $action
     * @return $action
     */
        
    public function getResourceDescription($resource)
    {
        if (isset($this->_resourceDescriptions[$resource])) {
            return $this->_resourceDescriptions[$resource];
        } else {
            return $action;
        }
    }
        
    public function getActionDescription($action)
    {
        if (isset($this->_actionDescriptions[$action])) {
            return $this->_actionDescriptions[$action];
        } else {
            return $action;
        }
    }

    /**
     * Rebuils the access list into a file
     *
     */
    public function rebuild()
    {
        $acl = new AclMemory();

        $acl->setDefaultAction(\Phalcon\Acl::DENY);

        //Register roles
        $profiles = Profiles::find('active = "Y"');

        foreach ($profiles as $profile) {
            $acl->addRole(new AclRole($profile->name));
        }

        foreach ($this->_privateResources as $resource => $actions) {
            $acl->addResource(new AclResource($resource), $actions);
        }

        //Grant acess to private area to role Users
        foreach ($profiles as $profile) {

            //Grant permissions in "permissions" model
            foreach ($profile->getPermissions() as $permission) {
                $acl->allow($profile->name, $permission->resource, $permission->action);
            }

            //Always grant these permissions
            $acl->allow($profile->name, 'users', 'changePassword');
        }

        return $acl;
    }
}
