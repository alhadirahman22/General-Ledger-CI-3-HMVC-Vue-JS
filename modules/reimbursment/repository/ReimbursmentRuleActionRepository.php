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
        $reimbursment_id = $id;
        $data = Reimbursment_model_eloquent::with(['reimbursment_department.approval.employee', 'reimbursment_department.department', 'reimbursment_department.approval.log'])->where('reimbursment_id', $reimbursment_id)->where('status', '!=', '1')->first();
        if ($data) {
            $data = $data->toArray();
            $reimbursment_department = $data['reimbursment_department'];
            $bool = false;
            for ($i = 0; $i < count($reimbursment_department); $i++) {
                $condition = $reimbursment_department[$i]['condition'];
                $department_idData = $reimbursment_department[$i]['department_id'];
                if ($department_idData == $department_id && $condition == '1') {
                    $approval =  $reimbursment_department[$i]['approval'];
                    for ($j = 0; $j < count($approval); $j++) {
                        $employee_idApproval = $approval[$j]['employee_id'];
                        if ($employee_idApproval == $employee_id) {
                            $bool = true;
                            break;
                        }
                    }
                }

                if ($bool) {
                    break;
                }
            }

            if ($bool) {
                $return = array('message' => '', 'status' => 'success');
            } else {
                $return = array('message' => 'You are not allowed to approval', 'status' => 'error');
            }
        } else {
            $return = array('message' => 'You are not allowed to approval', 'status' => 'error');
        }

        return $return;
    }
    public function edit($id)
    {
    }
}
