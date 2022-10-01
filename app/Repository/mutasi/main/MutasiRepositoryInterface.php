<?php

namespace Repository\mutasi\main;

interface MutasiRepositoryInterface
{
    public function getApprovalData($jenis_mutasi_id);
    public function getApprovalDataToInsert($jenis_mutasi_id);
    public function datatable($start, $length, $filter, $order, $tableParam);
    public function setOutputDatatable($get_data, $draw);
    public function validationManually($dataPost);
    public function create($dataPost);
    public function delete($id);
    public function movePositionBenda($benda_id);
    public function edit($id);
    public function findByID($id);
    public function loadApprovalMutasi($mutasi_benda_id);
    public function approve($item2);
    public function reject($item2);
}
