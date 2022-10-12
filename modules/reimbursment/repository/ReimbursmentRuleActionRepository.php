<?php

namespace Modules\reimbursment\repository;

use Modules\reimbursment\models\Reimbursment_model_eloquent;
use Modules\reimbursment\repository\main\ReimbursmentRepositoryRuleActionInterface;


class ReimbursmentRuleActionRepository implements ReimbursmentRepositoryRuleActionInterface
{
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function create($id)
    {
    }
    public function delete($id)
    {
        $reimbursment_id  = $id;
        $data = Reimbursment_model_eloquent::with(['reimbursment_department.approval.employee', 'reimbursment_department.department', 'reimbursment_department.approval.log'])->where('reimbursment_id', $reimbursment_id)->where('status', '=', '0')->first();
        if ($data) {
            $return = array('message' => '', 'status' => 'success');
        } else {
            $return = array('message' => 'This item not allowed to delete action', 'status' => 'error');
        }
        return $return;
    }
    public function approve($id, $department_id, $employee_id)
    {
    }
    public function edit($id)
    {
    }
}
