<?php

namespace Modules\reimbursment\repository;

use Modules\reimbursment\repository\main\ReimbursmentLogInterface;
use Modules\reimbursment\models\Reimbursment_approval_log_model_eloquent;



class ReimbursmentLogRepository implements ReimbursmentLogInterface
{
    protected $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function getData($id)
    {
    }
    public function setOutput($data)
    {
    }
    public function getParentToChild($id)
    {
    }
    public function insertLog($dataSaved)
    {
        Reimbursment_approval_log_model_eloquent::create($dataSaved);
    }
}
