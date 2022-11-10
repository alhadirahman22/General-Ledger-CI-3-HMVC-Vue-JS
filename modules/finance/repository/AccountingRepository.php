<?php

namespace Modules\finance\repository;

use Illuminate\Database\Capsule\Manager as Capsule;



class AccountingRepository
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function getCoaAktiva()
    {
    }

    public function getCoaPassiva()
    {
    }
}
