<?php

namespace Modules\Backend\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Modules\Models\UsuallyQuestion;
use Modules\Models\Answers;
use Modules\Models\Subdomain;
use Modules\Forms\UsuallyQuestionForm;
use Modules\Forms\AnswersForm;
use Phalcon\Http\Response;

class UsuallyQuestionController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Câu hỏi thường gặp';
        $this->assets->addJs("assets/js/ajaxupload.js");
    }

    public function indexAction()
    {
        $usuallyQuestion = UsuallyQuestion::findFirstBySubdomainId($this->_get_subdomainID());
        if (!$usuallyQuestion) {
            $usuallyQuestion = new UsuallyQuestion();
            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'name' => 'Câu hỏi thường gặp',
                'slug' => $this->general->create_slug('Câu hỏi thường gặp'),
                'active' => 'Y',
                'sort' => 1
            ];
            $usuallyQuestion->assign($data);
            $usuallyQuestion->save();
        }

        $answers = Answers::find([
            "conditions" => "usually_question_id = $usuallyQuestion->id AND deleted = 'N'",
            "order" => "sort ASC, id DESC",
        ]);

        $form = new UsuallyQuestionForm($usuallyQuestion, array('edit' => true));

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $request = $this->request->getPost();
            $data = [
                'name' => $this->request->getPost('name'),
                'slug' => $this->general->create_slug($this->request->getPost('name')),
                'slogan' => $this->request->getPost('slogan'),
                'photo' => $this->request->getPost('us_photo'),
            ];

            $usuallyQuestion->assign($data);
            if ($usuallyQuestion->save()) {
                if (count($answers) > 0) {
                    foreach ($answers as $answer) {
                        $id = $answer->id;
                        $active = isset($request['active'][$id]) ? 'Y' : 'N';

                        $dataAnswer = [
                            'name' => $request['name'][$id],
                            'active' => $active,
                            'question' => $request['question'][$id],
                            'answer' => $request['answer'][$id],
                            'sort' => $request['sort'][$id],
                        ];

                        $answer->assign($dataAnswer);
                        $answer->save();
                    }
                }

                
                $this->flashSession->success($this->_message["edit"]);
                $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName());
            }
        }

        $this->view->answers = $answers;
        $this->view->title_bar = $this->view->module_name;
        $breadcrumb = '<li class="active">'. $this->view->module_name .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->usuallyQuestion = $usuallyQuestion;
        $this->view->form = $form;
    }

    public function createAction($usuallyQuestionId)
    {
        if ($this->request->isAjax()) {
            $this->view->setTemplateBefore('form');
            $form = new AnswersForm();
            if ($this->request->isPost() && $this->request->getPost('usually_question_id') && $form->isValid($this->request->getPost())==true) {
                $this->view->disable();
                $data = [
                    'subdomain_id' => $this->_get_subdomainID(),
                    'usually_question_id' => $usuallyQuestionId,
                    'question' => $this->request->getPost('question'),
                    'answer' => $this->request->getPost('answer'),
                    'active' => 'Y',
                    'sort' => $this->request->getPost('sort'),
                ];

                $answers = new Answers();
                $answers->assign($data);
                if ($answers->save()) {
                    $this->flashSession->success($this->_message["add"]);
                    $result = 1;
                } else {
                    $result = 0;
                }

                $response = new Response();
                $response->setContent($result);
                return $response;
            }

            $this->view->usually_question_id = $usuallyQuestionId;
            $this->view->form = $form;
            $this->view->pick($this->_getControllerName() . '/form');
        }
    }

    public function updateAction($id)
    {
    }

    public function showAction($id, $page = 1)
    {
        $item = UsuallyQuestion::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders?active=customer_comment';
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign(array(
            'active' => 'Y',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideAction($id, $page = 1)
    {
        $item = UsuallyQuestion::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        // $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders?active=customer_comment';
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign(array(
            'active' => 'N',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function deleteAction($id, $page = 1)
    {
        $item = UsuallyQuestion::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'deleted' => 'Y',
        ));

        if ($item->save()) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders?active=customer_comment';
        $this->response->redirect($url);
    }

    public function deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = UsuallyQuestion::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item->assign(array(
                    'deleted' => 'Y',
                ));
                $item->save();
                $d++;
            }
        }
        //echo $d;die;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders?active=customer_comment';
        if ($d > 0) {
            $this->flashSession->success($this->_message["delete"]);
            
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function _deleteAction($id, $page = 1)
    {
        $item = Answers::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            $this->flashSession->success($this->_message["delete"]);
        }

        $url = ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    public function _deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Answers::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);

            if ($item->delete()) {
                $d++;
            }
        }
        
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if ($d > 0) {
            
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }
}
