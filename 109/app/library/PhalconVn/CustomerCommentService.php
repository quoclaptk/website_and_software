<?php

namespace Modules\PhalconVn;

use Modules\Models\CustomerComment;
use Modules\PhalconVn\MainGlobal;

class CustomerCommentService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getListCustomerComment()
    {
        $customerComments = CustomerComment::find([
            'columns' => 'id, name, photo, comment',
            'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND active = "Y" AND deleted = "N"',
            'order' => 'sort ASC, id DESC'
        ]);
        
        return $customerComments;
    }
}
