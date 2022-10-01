<?php

namespace Repository\mutasi;

use Repository\mutasi\main\MutasiLogRepositoryInterface;
use Modules\mutasi\models\Mutasi_benda_approval_log_model_eloquent;


class MutasiLogRepository implements MutasiLogRepositoryInterface
{
    protected $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function getData($id)
    {
    }
    public function setOutput($data)
    {
    }
    public function getParentToChild($id)
    {
    }
    public function insertLog($dataSaved)
    {
        Mutasi_benda_approval_log_model_eloquent::create($dataSaved);
    }
}
