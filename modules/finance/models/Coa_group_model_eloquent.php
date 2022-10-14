<?php

namespace Modules\finance\models;

defined('BASEPATH') or exit('No direct script access allowed');


use Illuminate\Database\Eloquent\Model as Eloquent;

class Coa_group_model_eloquent extends Eloquent
{
    protected $table = 'fin_coa_group';
    protected $primaryKey = 'fin_coa_group_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function coa()
    {
        return $this->hasMany(Coa_model_eloquent::class, 'fin_coa_group_id', 'fin_coa_group_id');
    }
}
