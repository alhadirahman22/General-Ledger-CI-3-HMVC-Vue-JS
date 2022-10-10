<?php

namespace Modules\reimbursment\repository\main;

interface ReimbursmentLogInterface
{
    public function getData($id);
    public function setOutput($data);
    public function getParentToChild($id);
    public function insertLog($dataSaved);
}
