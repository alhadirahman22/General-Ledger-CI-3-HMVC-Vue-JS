<?php

namespace Modules\finance\models;

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\finance\models\Coa_group_model_eloquent;

class Coa_model_eloquent extends Eloquent
{
    protected $table = 'fin_coa';
    protected $primaryKey = 'fin_coa_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function group_coa()
    {
        return $this->belongsTo(Coa_group_model_eloquent::class, 'fin_coa_group_id', 'fin_coa_group_id');
    }
}
