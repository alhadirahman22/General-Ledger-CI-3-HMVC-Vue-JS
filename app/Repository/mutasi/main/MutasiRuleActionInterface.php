<?php

namespace Repository\mutasi\main;

interface MutasiRuleActionInterface
{

    public function createMutasi($id, $jenis_mutasi_id);
    public function deleteMutasi($id);
    public function approveMutasi($id, $department_id, $employee_id); // by type_approval series or paralel
    public function editMutasi($id);
}
