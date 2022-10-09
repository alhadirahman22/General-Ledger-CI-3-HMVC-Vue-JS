<?php

namespace Repository\mutasi\main;

interface MutasiLogRepositoryInterface
{
    public function getData($id);
    public function setOutput($data);
    public function getParentToChild($id);
    public function insertLog($dataSaved);
}
