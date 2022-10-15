<?php

namespace Modules\finance\models;

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Model as Eloquent;


class Jurnal_voucher_model_eloquent extends Eloquent
{
    protected $table = 'fin_jurnal_voucher';
    protected $primaryKey = 'fin_jurnal_voucher_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
