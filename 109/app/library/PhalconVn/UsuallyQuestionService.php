<?php

namespace Modules\PhalconVn;

use Modules\Models\UsuallyQuestion;
use Modules\Models\Answers;

class UsuallyQuestionService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getListQuestionAnswers()
    {
        $usuallyQuestion = UsuallyQuestion::findFirst([
            'columns' => 'id, name, slogan, photo',
            'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND language_id = '. $this->_lang_id . ' AND active = "Y" AND deleted = "N"',
        ]);

        if ($usuallyQuestion) {
            $usuallyQuestion = $usuallyQuestion->toArray();
            $usuallyQuestion['answers'] = Answers::find([
                "columns" => "id, question, answer",
                "conditions" => "usually_question_id = " . $usuallyQuestion['id'] . " AND subdomain_id = $this->_subdomain_id AND active ='Y' AND deleted = 'N'",
                "order" => "sort ASC, id DESC"
            ]);
        }
        
        return $usuallyQuestion;
    }
}
