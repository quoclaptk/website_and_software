<?php 

namespace Modules\Backend\Controllers;

use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Security\Random;
use Phalcon\Image\Adapter\GD;
use Modules\Forms\AuthForm;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Glide\ServerFactory;
use League\Glide\Urls\UrlBuilderFactory;


class AuthController extends BaseController
{
    const IMAGE_VALID_FORMAT_TYPES = [
        "image/png",
        "image/jpg",
        "image/jpeg",
        "image/x-icon"
    ];
    public function onConstruct()
    {
        $this->view->module_name = 'Admin';
        $this->_message = $this->getMessage();
    }

    /**
     * Word create matching word on FO
     *
     * @return View
     */
    public function wordCreateAction()
    {
        $dirFile = 'messages/adminWord.json';
        $messages = [];
        if (!file_exists($dirFile)) {
            $fh = fopen($dirFile, 'w');
            fclose($fh);
        }

        chmod($dirFile, 0777);
        $data = $this->request->getPost();
        if ($this->request->isPost()) {
            if (file_exists($dirFile)) {
                $getContent = file_get_contents($dirFile);
                if (empty($getContent)) {
                    $array[$data['word_key']] = $data['name'];
                    $encodedString = json_encode($array);
                    file_put_contents($dirFile, $encodedString);
                    //Retrieve the data from our text file.
                    $fileContents = file_get_contents($dirFile);
                    //Convert the JSON string back into an array.
                    $decoded = json_decode($fileContents, true);
                } else {
                    $words = json_decode(file_get_contents($dirFile));
                    foreach ($words as $key => $word) {
                        $messages[$key] = $word;
                     } 

                     if (!isset($messages[$data['word_key']])) {
                        $messages[$data['word_key']] = $data['name'];
                     }
                     
                    file_put_contents($dirFile, json_encode($messages, JSON_UNESCAPED_UNICODE));
                }

            }
            
            $url = ACP_NAME . '/' . $this->_getControllerName() . '/' . $this->_getActionName();
            $this->response->redirect($url);
        }

        $form = new AuthForm();
        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->setVar('form', $form);
        $this->view->setVar('breadcrumb', $breadcrumb);
        // $getWord = json_decode(file_get_contents($dirFile));

    }

    /**
     * edit word can justify words on FO
     *
     * @return View
     */
    public function wordEditAction() 
    {
        $dirFile = 'messages/adminWord.json';
        $getContents = json_decode(file_get_contents($dirFile));
        $messages = [];
        foreach ($getContents as $key => $getContent) {
            $messages[$key] = $getContent;
        }

        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            file_put_contents($dirFile, json_encode($data['name'], JSON_UNESCAPED_UNICODE));
             $url = ACP_NAME . '/' . $this->_getControllerName() . '/' . $this->_getActionName();
             $this->response->redirect($url);
        }

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->setVar('breadcrumb', $breadcrumb);
        $this->view->setVar('messages', $messages);
    }

    /**
     * css for Admin login 
     * 
     * @return View
     */
    public function cssAction()
    {
        $this->assets->addCss('backend/bower_components/codemirror/lib/codemirror.css');
        $this->assets->addCss('backend/bower_components/codemirror/addon/hint/show-hint.css');
        $this->assets->addJs('backend/bower_components/codemirror/lib/codemirror.js');
        $this->assets->addJs('backend/bower_components/codemirror/mode/css/css.js');
        $this->assets->addJs('backend/bower_components/codemirror/addon/hint/show-hint.js');
        $this->assets->addJs('backend/bower_components/codemirror/addon/hint/css-hint.js');
        $this->assets->addJs('backend/dist/js/codemirrorConfig.js');

        $file = 'backend/dist/css/adminLogin.css';
        // dir folder
        $folder_img = 'uploads';
        // check folder has exitst 
        if ( !is_dir( $folder_img ) ) {
            // create folder
            mkdir( $folder_img );       
        }

        if ($this->request->isPost()) {
            $save_close = $this->request->getPost('save_close');
            file_put_contents($file, "");
            file_put_contents($file, $this->request->getPost("css"), FILE_APPEND | LOCK_EX);
            $this->flashSession->success("Cập nhật dữ liệu thành công");
        
            if (!empty($save_close)) {
                $url = ACP_NAME;
            } else {
                $url = ACP_NAME . '/' . $this->_getControllerName() . "/" . $this->_getActionName();
            }

            // check input get file to upload
            if ($this->request->hasFiles(true)) {
                foreach ($this->request->getUploadedFiles() as $file) {
                    // check file type format can upload 
                    if(in_array($file->getType(), self::IMAGE_VALID_FORMAT_TYPES)) {
                        // move image into folder
                        $file->moveTo($folder_img .'/'. 'favicon.ico');
                    }
                }
            }
            $this->response->redirect($url);
        }
        $cssFile = file_get_contents($file);
        // check null into folder
        $has_file = 'null';
        if (file_exists($folder_img . '/favicon.ico')) {
            $has_file = 'has file';
        }


        $this->view->title_bar = 'Css giao diện đăng nhập admin';
        $this->view->module_name = 'Css giao diện đăng nhập admin';
        $breadcrumb = '<li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->css_file = $cssFile;
        $this->view->has_file = $has_file;
        
    }

    /**
     * remove file in folder
     * 
     * @return View
     */
    public function deleteImateAction()
    {
        $folder_img = PROJECT_PATH.'/public/uploads';
        $file = 'favicon.ico';
        //remove file
        unlink($folder_img.'/'.$file);
        $url = ACP_NAME . '/' . $this->_getControllerName() . "/" . "css";
        $this->flashSession->success("Xóa favicon thành công");
        $this->response->redirect($url);
    }

}
