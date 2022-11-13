<?php

namespace Modules\finance\models;

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\finance\models\Coa_saldo_model_eloquent;

class Coa_saldo_history_model_eloquent extends Eloquent
{
    protected $table = 'fin_coa_saldo_history';
    protected $primaryKey = 'fin_coa_saldo_history_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = ['fin_coa_saldo_id', 'id_refer', 'table_name', 'desc', 'value', 'current_value', 'become_value', 'type_value', 'date_trans'];


    public function coa_saldo()
    {
        return $this->belongsTo(Coa_saldo_model_eloquent::class, 'fin_coa_saldo_id', 'fin_coa_saldo_id');
    }
}
