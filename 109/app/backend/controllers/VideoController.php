<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Video;
use Modules\Forms\VideoForm;
use Modules\Frontend\Controllers\BaseController;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Text;
use Phalcon\Image\Adapter\GD;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;

class VideoController extends \Modules\Backend\Controllers\BaseController
{
    public function initialize()
    {
        $this->view->setTemplateBefore('private');
    }

    /**
     * Default action, shows the search form
     */
    public function indexAction()
    {

        //$numberPage = 1;
        $video = $this->modelsManager->createBuilder()
            ->columns('video.*, category_video.name category_video_name')
            ->addFrom('Modules\Models\Video', 'video')
            ->leftJoin('Modules\Models\CategoryVideo', 'category_video.id = video.category_video_id', 'category_video')
            ->orderBy('video.id DESC')
            ->getQuery()
            ->execute();

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            array(
                "data" => $video,
                "limit" => 10,
                "page" => $numberPage
            )

        );


        $this->view->page = $paginator->getPaginate();
    }


    /**
     * Creates a User
     *
     */
    public function createAction()
    {
        $form = new VideoForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $date = date('d-m-Y', time());
            $video = new Video();
            $general = new General();
            $slug = $general->create_slug($this->request->getPost('name'));

            $data = array(
                'name' => $this->request->getPost('name'),
                'category_video_id' => $this->request->getPost('category_video_id'),
                'slug' => $slug,
                'sort' => $this->request->getPost('sort'),
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'active' => $this->request->getPost('active'),
                'folder' => $date,
            );


            $subCode = Text::random(Text::RANDOM_ALNUM, 20);
            if ($this->request->getPost('file')) {
                if (!is_dir("files/video/" . $date)) {
                    mkdir("files/video/" . $date, 0777);
                }
                $file = 'jqueryupload/php/files/' . $this->request->getPost('file');
                rename($file, 'files/video/'  . $date . '/' . $subCode . '.mp4');
                $data['file'] = $subCode . '.mp4';
            }

            if ($this->request->getPost('photo')) {
                $photo = $this->request->getPost('photo');
                $photo = str_replace('data:image/png;base64,', '', $photo);
                $photo = str_replace(' ', '+', $photo);
                $fileData = base64_decode($photo);
                //saving
                $fileName = $subCode . '.jpg';
                if (!is_dir("files/video_media/media/" . $date)) {
                    mkdir("files/video_media/media/" . $date, 0777);
                }

                $fileNameFolder = 'files/video_media/media/' . $date . '/' . $fileName;
                if (file_put_contents($fileNameFolder, $fileData)) {
                    $data['photo'] = $fileName;

                    /*if (!is_dir("files/video_media/thumb/320x180")) {
                        mkdir("files/video_media/thumb/320x180", 0777);
                    }

                    if (!is_dir("files/video_media/thumb/320x180/" . $date)) {
                        mkdir("files/video_media/thumb/320x180/" . $date, 0777);
                    }

                    if (!is_dir("files/video_media/thumb/300x168")) {
                        mkdir("files/video_media/thumb/300x168", 0777);
                    }
                    if (!is_dir("files/video_media/thumb/300x168/" . $date)) {
                        mkdir("files/video_media/thumb/300x168/" . $date, 0777);
                    }

                    if (!is_dir("files/video_media/thumb/200x112")) {
                        mkdir("files/video_media/thumb/200x112", 0777);
                    }
                    if (!is_dir("files/video_media/thumb/200x112/" . $date)) {
                        mkdir("files/video_media/thumb/200x112/" . $date, 0777);
                    }

                    $image = new GD($fileNameFolder);
                    $image->resize(320, 180);
                    $image->save("files/video_media/thumb/320x180/" . $date . '/' . $fileName);
                    $image->resize(300, 168);
                    $image->save("files/video_media/thumb/300x168/" . $date . '/' . $fileName);
                    $image->resize(200, 112);
                    $image->save("files/video_media/thumb/200x112/" . $date . '/' . $fileName);*/
                }
            }

            $video->assign($data);


            if ($video->save()) {
                $this->flashSession->success("Thêm mới video thành công");

                return $this->response->redirect('acp/video');
            } else {
                $this->flash->error($video->getMessages());
            }
        }

        $this->view->form = $form;
    }

    /**
     * Saves the user from the 'edit' action
     *
     */
    public function updateAction($id)
    {
        //echo '//'.$_SERVER["SERVER_NAME"].'/bongdaviet/home/files/';
        $video = Video::findFirstById($id);
        if (!$video) {
            $this->flash->error("Không tìm thấy bài viết");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $fileVideo = $video->file;
        $filePhoto = $video->photo;
        $folder = $video->folder;
        $date = date('d-m-Y', time());
        $form = new VideoForm($video, array('edit' => true));
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $general = new General();
            $slug = $general->create_slug($this->request->getPost('name'));

            $data = array(
                'name' => $this->request->getPost('name'),
                'category_video_id' => $this->request->getPost('category_video_id'),
                'slug' => $slug,
                'sort' => $this->request->getPost('sort'),
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'active' => $this->request->getPost('active'),
            );

            $subCode = Text::random(Text::RANDOM_ALNUM, 20);


            if ($this->request->getPost('file') != '') {
                if (!is_dir("files/video/" . $folder)) {
                    mkdir("files/video/" . $folder, 0777);
                }
                $file = 'jqueryupload/php/files/' . $this->request->getPost('file');
                rename($file, 'files/video/' . $folder . '/' . $subCode . '.mp4');
                $data['file'] = $subCode . '.mp4';
                @unlink('files/video/' . $folder . '/' . $fileVideo);
            }



            if ($this->request->getPost('photo') != '') {
                $photo = $this->request->getPost('photo');
                $photo = str_replace('data:image/png;base64,', '', $photo);
                $photo = str_replace(' ', '+', $photo);
                $fileData = base64_decode($photo);
                //saving

                $fileName = $subCode . '.jpg';
                $fileNameFolder = 'files/video_media/media/' . $folder . '/' . $fileName;
                if (file_put_contents($fileNameFolder, $fileData)) {
                    $data['photo'] = $fileName;

                    /*if (!is_dir("files/video_media/thumb/320x180")) {
                        mkdir("files/video_media/thumb/320x180", 0777);
                    }
                    if (!is_dir("files/video_media/thumb/320x180/" . $folder)) {
                        mkdir("files/video_media/thumb/320x180/" . $folder, 0777);
                    }

                    if (!is_dir("files/video_media/thumb/300x168")) {
                        mkdir("files/video_media/thumb/300x168", 0777);
                    }
                    if (!is_dir("files/video_media/thumb/300x168/" . $folder)) {
                        mkdir("files/video_media/thumb/300x168/" . $folder, 0777);
                    }

                    if (!is_dir("files/video_media/thumb/200x112")) {
                        mkdir("files/video_media/thumb/200x112", 0777);
                    }
                    if (!is_dir("files/video_media/thumb/200x112/" . $folder)) {
                        mkdir("files/video_media/thumb/200x112/" . $folder, 0777);
                    }

                    $image = new GD($fileNameFolder);
                    $image->resize(320, 180);
                    $image->save("files/video_media/thumb/320x180/" . $folder . '/' . $fileName);
                    $image->resize(300, 168);
                    $image->save("files/video_media/thumb/300x168/" . $folder . '/' . $fileName);
                    $image->resize(200, 112);
                    $image->save("files/video_media/thumb/200x112/" . $folder . '/' . $fileName);*/

                    @unlink('files/video_media/media/' . $folder . '/'  . $filePhoto);
                    @unlink('files/video_media/thumb/320x180/' . $folder . '/'  . $filePhoto);
                    @unlink('files/video_media/thumb/300x168/' . $folder . '/'  . $filePhoto);
                    @unlink('files/video_media/thumb/200x112/' . $folder . '/'  . $filePhoto);
                }
            }


            $video->assign($data);

            if ($video->save()) {
                $this->flash->success("Cập nhật thành công");

            //return $this->response->redirect('acp/news');
                //return $this->response->redirect('acp/' . $this->router->getControllerName() . '/update/' . $id);
            } else {
                $this->flash->error($video->getMessages());
            }
        }

        $this->view->video = $video;
        $this->view->form = $form;
    }

    protected function extFileCheck($extension)
    {
        $allowedTypes = [
            'video/mp4',
        ];

        return in_array($extension, $allowedTypes);
    }

    public function showAction($id)
    {
        $video = Video::findFirstById($id);
        if (!$video) {
            $this->flash->error("Video không tìm thấy");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $video->assign(array(
            'active' => 'Y',
        ));

        if ($video->save()) {
            $this->flash->success("Hiển thị bài viết thành công");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function showmultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $video = Video::findFirstById($id);
            if ($video) {
                $video->assign(array(
                    'active' => 'Y',
                ));
                $video->save();
                $d++;
            }
        }

        if ($d > 0) {
            $this->flash->success("Bài viết hiển thị thành công");
        } else {
            $this->flash->error("Không tìm thấy bài viết");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function hideAction($id)
    {
        $video = Video::findFirstById($id);
        if (!$video) {
            $this->flash->error("Không tìm thấy bài viết");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $video->assign(array(
            'active' => 'N',
        ));

        if ($video->save()) {
            $this->flash->success("Ẩn bài viết thành công");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function hidemultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $video = Video::findFirstById($id);
            if ($video) {
                $video->assign(array(
                    'active' => 'N',
                ));
                $video->save();
                $d++;
            }
        }

        if ($d > 0) {
            $this->flash->success("Ẩn video thành công");
        } else {
            $this->flash->error("Không tìm thấy video");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }


    /**
     * Deletes a News
     *
     * @param int $id
     */
    public function deleteAction($id)
    {
        $video = Video::findFirstById($id);
        $fileVideo = $video->file;
        $filePhoto = $video->photo;
        if (!$video) {
            $this->flash->error("Không tìm thấy bài viết");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$video->delete()) {
            $this->flash->error($video->getMessages());
        } else {
            @unlink('files/video/' . $video->folder . '/' . $fileVideo);
            @unlink('files/video_media/media/' . $video->folder . '/' . $filePhoto);
            @unlink('files/video_media/thumb/320x180/' . $video->folder . '/' . $filePhoto);
            @unlink('files/video_media/thumb/300x168/' . $video->folder . '/' . $filePhoto);
            @unlink('files/video_media/thumb/200x112/' . $video->folder . '/' . $filePhoto);
            $this->flash->success("Bài viết đã được xóa");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function deletemultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $video = Video::findFirstById($id);
            if ($video) {
                $fileVideo = $video->file;
                $filePhoto = $video->photo;
                if ($video->delete()) {
                    @unlink('files/video/' . $video->folder . '/' . $fileVideo);
                    @unlink('files/video_media/media/' . $video->folder . '/' . $filePhoto);
                    @unlink('files/video_media/thumb/320x180/' . $video->folder . '/' . $filePhoto);
                    @unlink('files/video_media/thumb/300x168/' . $video->folder . '/' . $filePhoto);
                    @unlink('files/video_media/thumb/200x112/' . $video->folder . '/' . $filePhoto);
                }
                $d++;
            }
        }
        //echo $d;die;
        if ($d > 0) {
            $this->flash->success("Video đã được xóa");
        } else {
            $this->flash->error("Không tìm thấy video");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }
}
