<?php

namespace Modules\reimbursment\repository\main;

interface ReimbursmentRepositoryRuleActionInterface
{

    public function create($id);
    public function delete($id);
    public function approve($id, $department_id, $employee_id); // by type_approval series or paralel
    public function edit($id);
}
