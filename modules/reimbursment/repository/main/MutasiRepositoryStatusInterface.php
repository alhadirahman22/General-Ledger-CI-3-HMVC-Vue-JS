<?php

namespace Repository\mutasi\main;


interface MutasiRepositoryStatusInterface
{

    const statusData = [
        '1' => 'Approved',
        // '0' => 'Not Action',
        '0' => 'Waiting',
        '-1' => 'Reject',
        '2' => 'Progress',
        '-2' => 'Awaiting',
    ];

    const condition = [
        '1' => 'now',
        '0' => 'unknown',
        '2' => 'done'
    ];

    public function updateStatusFirst($mutasi_benda_id, $status = '2');
    public function updateAfterApproval($mutasi_benda_id, $indexApproved, $indexApprovedDept);
    public function currentStatus($mutasi_benda_id);
    public function updateStatusWhenIsDone($mutasi_benda_id);
    public function afterReject($mutasi_benda_id, $indexApproved, $indexApprovedDept);
    public function findCondition1($data);
    public function findConditionRejected($data);
    public function statusBendaMutasi($benda_id);
}
