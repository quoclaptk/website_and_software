<?php namespace Modules;

use Phalcon\Mvc\User\Component;
use Phalcon\Forms\Form;

/**
 * Elements
 *
 * Helps to build UI elements for the application
 */
class Elements extends Component
{
    private $_headerMenu = array(
        'pull-left' => array(
            'index' => array(
                'caption' => '<img alt="Logo-small" src="../img/logofront.png"/>',
                'action' => ''
            ),
        ),
        'pull-right' => array(
            'users' => array(
                'caption' => 'Users',
                'action' => 'index'
            ),
            'contact' => array(
                'caption' => 'Contact',
                'action' => 'index'
            ),
            'session' => array(
                'caption' => 'Log In/Sign Up',
                'action' => 'index'
            ),
            'about' => array(
                'caption' => 'About',
                'action' => 'index'
            ),
            'search'=> array(
                'caption' => '<form action="" id="search-form" method="get">
                          <input name="search[q]" type="text" placeholder="Search ..." id="book-search" />
                          </form>',
                'action' => 'index'
            ),
        )
    );

    
    private $_tabs = array(
        'hososinhvien' => array(
            '<i class="fa fa-plus-circle"></i> Thêm sinh viên'  => 'create',
            '<i class="fa fa-pencil-square-o"></i> Cập nhật thông tin'=>'edit',
            '<i class="fa fa-trash-o"></i>Xóa sinh viên'        => 'delete',
            '<i class="fa fa-cloud-upload"></i>Nhập DSSV từ tập tin'=>'import',
            '<i class="fa fa-cloud-download"></i>Xuất DSSV ra tập tin'=>'export'
        ),
        'giaovien' => array(
             'Them Sinh Vien'=>'create',
             'Xoa Snh vien' =>'delete',
             'sua sinh vien' =>'edit'
        )
    );
    private $_title = array(
            'hososinhvien'  => 'Hồ Sơ Sinh Viên',
            'giangvien'     => 'Giảng Viên'
    );

    /**
     * Builds header menu with left and right items
     *
     * @return string
     */
    public function getMenu()
    {
        $auth = $this->session->get('auth-front');
        if ($auth) {
            $this->_headerMenu['pull-right']['session'] = array(
                'caption' => 'Log Out',
                'action' => 'logout'
            );
        } else {
            unset($this->_headerMenu['pull-right']['users']);
        }
        echo '<div class="nav-collapse">';
        $controllerName = $this->view->getControllerName();
        foreach ($this->_headerMenu as $position => $menu) {
            echo '<ul class="nav ', $position, '">';
            foreach ($menu as $controller => $option) {
                if ($controllerName == $controller) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
                if ($controller=="search") {
                    echo $option['caption'];
                } else {
                    echo \Phalcon\Tag::linkTo($controller.'/'.$option['action'], $option['caption']);
                }
                echo '</li>';
            }
            echo '</ul>';
        }
        echo '</div>';
    }

    /**
     * Builds tabs element import,edit....
     * @return [type] [description]
     */
    public function getTabs()
    {
        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        echo '<ul id="span10" <ul class="nav nav-tabs">';
        foreach ($this->_tabs as $controller => $option) {
            if ($controller == $controllerName) {
                foreach ($option as $caption => $action) {
                    echo "<li>";
                    if ($action === 'import' || $action === 'export') {
                        echo \Phalcon\Tag::linkTo(array('#myModal'.$action,$caption,'data-toggle'=>'modal'));
                    } else {
                        echo \Phalcon\Tag::linkTo('admin/'.$controller.'/'.$action, $caption);
                    }
                    echo "</li>";
                }
            }
        }
        echo '</ul>';
    }
    /**
     * display information Capton Tiltle Controller,in private.volt
     * @return [type] [description]
     */
    public function getTitle()
    {
        $controllerName = $this->view->getControllerName();
        foreach ($this->_title as $controller => $option) {
            if ($controller==$controllerName) {
                return $option;
            }
        }
    }
}
