<?php

namespace Modules\finance\models;

defined('BASEPATH') or exit('No direct script access allowed');

use Modules\finance\models\Coa_model_eloquent;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Sia_model_eloquent extends Eloquent
{
    protected $table = 'sia';
    protected $primaryKey = 'sia_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = ['fin_coa_id', 'id_refer', 'table_name', 'debit', 'credit', 'desc', 'created_by', 'updated_by', 'customer_id', 'supplier_id', 'date_trans', 'id_refer_sub_1', 'table_name_sub_1', 'id_refer_sub_2', 'table_name_sub_2'];

    public function coa()
    {
        return $this->belongsTo(Coa_model_eloquent::class, 'fin_coa_id', 'fin_coa_id');
    }
}
