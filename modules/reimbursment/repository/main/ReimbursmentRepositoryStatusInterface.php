<?php

namespace Modules\reimbursment\repository\main;


interface ReimbursmentRepositoryStatusInterface
{

    const statusData = [
        '1' => 'Approved',
        // '0' => 'Not Action',
        '0' => 'Waiting',
        '-1' => 'Reject',
        '2' => 'Progress',
        '-2' => 'Awaiting',
        '99' => 'Terbayarkan'
    ];

    const condition = [
        '1' => 'now',
        '0' => 'unknown',
        '2' => 'done'
    ];

    public function updateStatusFirst($reimbursment_id, $status = '2');
    public function updateAfterApproval($reimbursment_id, $indexApproved, $indexApprovedDept);
    public function currentStatus($reimbursment_id);
    public function updateStatusWhenIsDone($reimbursment_id);
    public function afterReject($reimbursment_id, $indexApproved, $indexApprovedDept);
    public function findCondition1($data);
    public function findConditionRejected($data);
}
