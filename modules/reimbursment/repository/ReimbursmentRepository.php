<?php

namespace Modules\reimbursment\repository;

use Modules\reimbursment\models\Reimbursment_model_eloquent;
use Modules\reimbursment\repository\main\ReimbursmentRepositoryInterface;



class ReimbursmentRepository implements ReimbursmentRepositoryInterface
{
    protected $CI;
    public $statusMainApproval =   [
        '1' => 'Approved',
        '0' => 'Waiting',
        '-1' => 'Reject',
        '2' => 'Progress',
        '-2' => 'Awaiting',
        '99' => 'Terbayarkan'
    ];


    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function opStatus()
    {
        return $this->statusMainApproval;
    }

    public function getApprovalData($codeApproval)
    {
    }
    public function getApprovalDataToInsert($codeApproval)
    {
    }
    public function loadApprovalMutasi($codeApproval)
    {
    }
    public function approve($item2)
    {
    }
    public function reject($item2)
    {
    }

    public function datatable($start, $length, $filter, $order, $tableParam)
    {
    }
    public function setOutputDatatable($get_data, $draw)
    {
    }
    public function validationManually($dataPost)
    {
    }
    public function create($dataPost)
    {
    }
    public function delete($id)
    {
    }
    public function edit($id)
    {
    }
    public function findByID($id)
    {
        $reimbursment_id = $id;
        $data = Reimbursment_model_eloquent::with(['reimbursment_department.approval.employee', 'reimbursment_department.department', 'reimbursment_department.approval.log'])->where('reimbursment_id', $reimbursment_id)->first();
        if ($data) {
            return $data;
        }
        return [];
    }
}
