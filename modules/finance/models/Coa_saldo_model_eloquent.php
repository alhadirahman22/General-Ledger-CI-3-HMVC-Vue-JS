<?php

namespace Modules\finance\models;

defined('BASEPATH') or exit('No direct script access allowed');

use Modules\finance\models\Coa_model_eloquent;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Coa_saldo_model_eloquent extends Eloquent
{
    protected $table = 'fin_coa_saldo';
    protected $primaryKey = 'fin_coa_saldo_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = ['fin_coa_id', 'value', 'date', 'created_by'];

    public function coa()
    {
        return $this->belongsTo(Coa_model_eloquent::class, 'fin_coa_id', 'fin_coa_id');
    }

    public function histori()
    {
        return $this->hasMany(Coa_saldo_history_model_eloquent::class, 'fin_coa_saldo_id', 'fin_coa_saldo_id');
    }
}
