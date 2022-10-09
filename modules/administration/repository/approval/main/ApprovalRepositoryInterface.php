<?php

namespace Modules\administration\repository\approval\main;

interface ApprovalRepositoryInterface
{
    public function getApprovalData($codeApproval);
    public function getApprovalDataToInsert($codeApproval);
    public function loadApprovalMutasi($codeApproval);
    public function approve($item2);
    public function reject($item2);
}
