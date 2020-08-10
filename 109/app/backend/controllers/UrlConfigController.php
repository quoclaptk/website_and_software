<?php

namespace Modules\Backend\Controllers;

use Modules\Models\News;
use Modules\Models\UrlConfig;
use Modules\Forms\NewsAutoUploadForm;
use Modules\Forms\UrlConfigForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Image\Adapter\GD;
use Phalcon\Text as TextRandom;
use Modules\Models\Tags;
use Modules\Models\TmpNewsTags;

/**
 * Modules\Controllers\UsersController
 *
 * CRUD to manage users
 */
class UrlConfigController extends BaseController
{
    protected $_cName = 'url_config';

    public function initialize()
    {
        $this->view->setTemplateBefore('private');
        include('dom/simple_html_dom.php');
    }

    /**
     * Default action, shows the search form
     */
    public function indexAction()
    {
        $numberPage = 1;
        $list = UrlConfig::find(
            array(
                            "order" => "sort ASC, id DESC",
                        )
        );

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            array(
            "data" => $list,
            "limit" => 10,
            "page" => $numberPage
                )
        );

        $this->view->page = $paginator->getPaginate();
        //echo '<pre>'; print_r($category); echo '</pre>';die;
    }

    /**
     * Creates a User
     *
     */
    public function createAction()
    {
        $tags = Tags::find(array(
                    'colums' => 'id, name',
                    'conditions' => 'active="Y"',
                    'order' => 'sort ASC, id DESC'
        ));
        $form = new UrlConfigForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $item = new UrlConfig();

            $item->assign(array(
                'name' => $this->request->getPost('name'),
                'link' => $this->request->getPost('link'),
                'wrapper' => $this->request->getPost('wrapper'),
                'title' => $this->request->getPost('title'),
                'summary' => $this->request->getPost('summary'),
                'img' => $this->request->getPost('img'),
                'img_link_replace' => $this->request->getPost('img_link_replace'),
                'href' => $this->request->getPost('href'),
                'content' => $this->request->getPost('content'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
            ));

            if ($item->save()) {
                $this->flashSession->success("Thêm mới thành công");

                return $this->response->redirect('acp/' . $this->_cName);
            } else {
                $this->flash->error($item->getMessages());
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
        $item = UrlConfig::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $form = new UrlConfigForm($item, array('edit' => true));
        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $item->assign(array(
                'name' => $this->request->getPost('name'),
                'link' => $this->request->getPost('link'),
                'wrapper' => $this->request->getPost('wrapper'),
                'title' => $this->request->getPost('title'),
                'summary' => $this->request->getPost('summary'),
                'img' => $this->request->getPost('img'),
                'img_link_replace' => $this->request->getPost('img_link_replace'),
                'href' => $this->request->getPost('href'),
                'content' => $this->request->getPost('content'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
            ));
            if ($item->save()) {
                $this->flash->success("Cập nhật thành công");
            } else {
                $this->flash->error($item->getMessages());
            }
        }


        $this->view->form = $form;
    }

    public function createnewsAction()
    {
        $tags = Tags::find(array(
                    'colums' => 'id, name',
                    'conditions' => 'active="Y"',
                    'order' => 'sort ASC, id DESC'
        ));
        $form = new NewsAutoUploadForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $date = date('d-m-Y', time());
            $news = new News();
            $general = new General();
            $slug = $general->create_slug($this->request->getPost('name'));

            $data = array(
                'name' => $this->request->getPost('name'),
                'category_id' => $this->request->getPost('category_id'),
                'summary' => $this->request->getPost('summary'),
                'slug' => $slug,
                'sort' => $this->request->getPost('sort'),
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'active' => $this->request->getPost('active'),
                'folder' => $date,
            );

            if (!is_dir("files/news/" . $date)) {
                mkdir("files/news/" . $date, 0777);
            }
            $subCode = TextRandom::random(TextRandom::RANDOM_ALNUM);
            if ($general->upload_file_content('files/news/' . $date . '/', $this->request->getPost('img_get_content'), $subCode)) {
                $path_parts = pathinfo($this->request->getPost('img_get_content'));
                $file_name = $path_parts['filename'];
                $extension = $path_parts['extension'];
                $image_name = $general->create_slug($file_name) . '_' . $subCode . '.' . $extension;
                $data['photo'] = $image_name;

                if (!is_dir("files/news/thumb/612x340")) {
                    mkdir("files/news/thumb/612x340", 0777);
                }
                if (!is_dir("files/news/thumb/612x340/" . $date)) {
                    mkdir("files/news/thumb/612x340/" . $date, 0777);
                }

                if (!is_dir("files/news/thumb/500x300")) {
                    mkdir("files/news/thumb/500x300", 0777);
                }
                if (!is_dir("files/news/thumb/500x300/" . $date)) {
                    mkdir("files/news/thumb/500x300/" . $date, 0777);
                }

                if (!is_dir("files/news/thumb/280x200")) {
                    mkdir("files/news/thumb/280x200", 0777);
                }
                if (!is_dir("files/news/thumb/280x200/" . $date)) {
                    mkdir("files/news/thumb/280x200/" . $date, 0777);
                }

                if (!is_dir("files/news/thumb/114x80")) {
                    mkdir("files/news/thumb/114x80", 0777);
                }
                if (!is_dir("files/news/thumb/114x80/" . $date)) {
                    mkdir("files/news/thumb/114x80/" . $date, 0777);
                }

                if (!is_dir("files/news/thumb/90x60")) {
                    mkdir("files/news/thumb/90x60", 0777);
                }
                if (!is_dir("files/news/thumb/90x60/" . $date)) {
                    mkdir("files/news/thumb/90x60/" . $date, 0777);
                }

                $fileNameFolder = 'files/news/' . $date . '/' . $image_name;

                $image = new GD($fileNameFolder);
                $image->resize(620, 350)->crop(612, 340, 4, 5);
                $image->save("files/news/thumb/612x340/" . $date . '/' . $image_name);
                $image->resize(530, 320)->crop(500, 300, 15, 5);
                $image->save("files/news/thumb/500x300/" . $date . '/' . $image_name);
                $image->resize(310, 220)->crop(280, 200, 15, 5);
                $image->save("files/news/thumb/280x200/" . $date . '/' . $image_name);
                $image->resize(124, 86)->crop(114, 80, 5, 1);
                $image->save("files/news/thumb/114x80/" . $date . '/' . $image_name);
                $image->resize(95, 64)->crop(90, 60, 3, 1);
                $image->save("files/news/thumb/90x60/" . $date . '/' . $image_name);
            }

            $contentHtml = $this->request->getPost('content');
            if (!is_dir("files/images/news")) {
                mkdir("files/images/news", 0777);
            }
            if (!is_dir("files/images/news/" . $date)) {
                mkdir("files/images/news/" . $date, 0777);
            }

            $data['content'] = $contentHtml;

            if (!empty($contentHtml)) {
                preg_match_all('/<img[^>]+>/i', $contentHtml, $imgResult);
                if (!is_dir("files/images/news/" . $date . '/' . $slug)) {
                    mkdir("files/images/news/" . $date . '/' . $slug, 0777);
                }



                $imgUrlFolder = array();
                if (!empty($imgResult)) {
                    foreach ($imgResult[0] as $img_tag) {
                        $doc = new \DOMDocument();
                        $doc->loadHTML($img_tag);
                        $imageTags = $doc->getElementsByTagName('img');
                        foreach ($imageTags as $tag) {
                            $img_src = $tag->getAttribute('src');
                            $general->upload_file_content('files/images/news/' . $date . '/' . $slug, $img_src);
                            $path_parts = pathinfo($img_src);
                            $imgUrlFolder[] = $path_parts['dirname'];
                        }
                        $url = new Url();
                        $base_url = _URL . $url->getBaseUri();
                        $urlUpload = $base_url . 'files/images/news/' . $date . '/' . $slug;

                        $content = str_replace($imgUrlFolder, $urlUpload, $contentHtml);

                        $contentHtmlnew = preg_replace("/<a[^>]+\>/i", "", $content);
                        $data['content'] = $contentHtmlnew;
                    }
                }
            }
            

            $news->assign($data);

            if ($news->save()) {
                $tag_input = $this->request->getPost('tag');
               
                if (!empty($tag_input)) {
                    $tmp_news_tags = new TmpNewsTags();
                    foreach ($tag_input as $t) {
                        $tmp_news_tags->assign(array(
                          'news_id' => $news->id,
                          'tag_id' => $t
                      ));
                        if ($tmp_news_tags->save()) {
                            $this->flashSession->success("Thêm mới tags thành công");
                        } else {
                            $this->flash->error($tmp_news_tags->getMessages());
                        };
                    }
                }
                $this->flashSession->success("Thêm mới bài viết thành công");

                return $this->response->redirect('acp/news/update/' . $news->id);
            } else {
                $this->flash->error($news->getMessages());
            }
        }

        $site = new Select('select_site', UrlConfig::find('active = "Y"'), array(
            'using' => array('id', 'name'),
            'useEmpty' => true,
            'class' => 'form-control',
            'style' => 'max-width:50%'
        ));
        $link = new Text('link_get_content', array(
            'placeholder' => 'Enter Url',
            'id' => 'link_get_content',
            'class' => 'form-control',
            'style' => 'width: 80%'
        ));
        $img_get_content = new Text('img_get_content', array(
            'id' => 'img_get_content',
            'class' => 'form-control',
            'readonly' => true
        ));

        $this->view->site = $site;
        $this->view->link = $link;
        $this->view->img_get_content = $img_get_content;
        $this->view->tags = $tags;
        $this->view->form = $form;
    }

    public function loadnewsAction($id)
    {
        $url = $this->request->get('url');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $curl_html = curl_exec($ch);
        curl_close($ch);

        $html = str_get_html($curl_html);

        $config = UrlConfig::findFirstById($id);

        $configWrapper = json_decode($config->wrapper);
        $configTitle = json_decode($config->title);
        $configSummary = json_decode($config->summary, true);
        $configImg = json_decode($config->img, true);
        $configHref = json_decode($config->href, true);
        //print_r($configWrapper->tag);die;

        $data = array();
        $data['error'] = 'Không tìm thấy dữ liệu trên URL đã nhập.';
        $data['content'] = '';
        if (!empty($html)) {
            $articles = array();
            foreach ($html->find($configWrapper->tag) as $article) {
                $item['name'] = trim($article->find($configTitle->tag, $configTitle->node)->plaintext);
                $item['summary'] = trim($article->find($configSummary['tag'], $configSummary['node'])->plaintext);
                $item['img'] = trim($article->find($configImg['tag'], $configImg['node'])->src);
                $item['link'] = trim($article->find($configHref['tag'], $configHref['node'])->href);
                if (!empty($item['name'])) {
                    $articles[] = $item;
                }
            }


            if (!empty($articles)) {
                $newsArray = array();
                $news = $this->modelsManager->createBuilder()
                        ->columns('news.slug,news.name')
                        ->addFrom('Modules\Models\News', 'news')
                        ->where('active = "Y"')
                        ->orderBy('news.id DESC')
                        ->limit(100)
                        ->getQuery()
                        ->execute();
                if (!empty($news)) {
                    foreach ($news as $n) {
                        $newsArray[] = $n->slug;
                    }
                }
                //echo '<pre>'; print_r($newsArray);echo '</pre>';
                $general = new General();
                for ($i = 0; $i < count($articles); $i++) {
                    $slug_name = $general->create_slug(trim($articles[$i]['name']));
                    if (in_array($slug_name, $newsArray)) {
                        unset($articles[$i]);
                    }
                }
                //die;

                $data['content'] = '<option value="">Chọn</option>';
                if (!empty($articles)) {
                    $data['error'] = 'Lấy dữ liệu thành công. Chọn tin bên dưới để up lên.';
                    foreach ($articles as $item) {
                        $data['content'] .= "<option value='" . $item['name'] . "' data-site='" . $config->link . "' data-summary='" . $item['summary'] . "' data-img='" . $item['img'] . "' data-href='" . $item['link'] . "'>" . $item['name'] . "</option>";
                    }
                }
            } else {
                $data['error'] = 'Không tìm thấy dữ liệu trên URL đã nhập.';
            }
        } else {
            $data['error'] = 'Không tìm thấy dữ liệu trên URL đã nhập.';
        }


        echo json_encode($data);

        $this->view->disable();


        //$configContent =json_decode($config->content, true);
        //echo '<pre>';print_r($newsArray);echo '</pre>';die;
    }

    public function getimgreplaceAction()
    {
        $id = $this->request->getPost('id');
        $config = $this->modelsManager->createBuilder()
                ->columns('url_config.img_link_replace')
                ->addFrom('Modules\Models\UrlConfig', 'url_config')
                ->where('id =' . $id)
                ->getQuery()
                ->getSingleResult();
        echo $config->img_link_replace;
        $this->view->disable();
    }

    public function loadcontentAction()
    {
        $id = $this->request->get('id');
        $url = $this->request->get('url');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $curl_html = curl_exec($ch);
        curl_close($ch);

        $html = str_get_html($curl_html);

        $config = $this->modelsManager->createBuilder()
                ->columns('url_config.content')
                ->addFrom('Modules\Models\UrlConfig', 'url_config')
                ->where('id =' . $id)
                ->getQuery()
                ->getSingleResult();


        $configContent = json_decode($config->content, true);


        if (!empty($html)) {
            foreach ($html->find($configContent['tag']) as $article) {
                $content = trim($article->innertext, $configContent['node']);
            }
            echo $content;
        }
        $this->view->disable();
    }

    public function showAction($id)
    {
        $item = UrlConfig::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'active' => 'Y',
        ));

        if ($item->save()) {
            $this->flash->success("Hiển thị thành công");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function showmultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = UrlConfig::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'active' => 'Y',
                ));
                $item->save();
                $d++;
            }
        }

        if ($d > 0) {
            $this->flash->success("Hiển thị thành công");
        } else {
            $this->flash->error("Không tìm thấy dữ liệu");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function hideAction($id)
    {
        $item = UrlConfig::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'active' => 'N',
        ));

        if ($item->save()) {
            $this->flash->success("Ẩn dữ liệu thành công");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function hidemultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = UrlConfig::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'active' => 'N',
                ));
                $item->save();
                $d++;
            }
        }

        if ($d > 0) {
            $this->flash->success("Ẩn dữ liệu thành công");
        } else {
            $this->flash->error("Không tìm thấy dữ liệu");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }

    /**
     * Deletes a Category
     *
     * @param int $id
     */
    public function deleteAction($id)
    {
        $item = UrlConfig::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$item->delete()) {
            $this->flash->error($item->getMessages());
        } else {
            $this->flash->success("Xóa dữ liệu thành công");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function deletemultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = UrlConfig::findFirstById($id);
            if ($item) {
                $item->delete();
                $d++;
            }
        }
        //echo $d;die;
        if ($d > 0) {
            $this->flash->success("Xóa dữ liệu thành công");
        } else {
            $this->flash->error("Không tìm thấy dữ liệu");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }
}
