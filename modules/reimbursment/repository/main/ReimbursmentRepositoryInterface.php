<?php

namespace Modules\reimbursment\repository\main;

use Modules\administration\repository\approval\main\ApprovalRepositoryInterface;

interface ReimbursmentRepositoryInterface extends ApprovalRepositoryInterface
{
    public function datatable($start, $length, $filter, $order, $tableParam);
    public function setOutputDatatable($get_data, $draw);
    public function validationManually($dataPost);
    public function create($dataPost);
    public function delete($id);
    public function edit($id);
    public function findByID($id);
}
