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
    protected $fillable = ['fin_coa_id', 'id_refer', 'debit', 'credit', 'desc', 'created_by', 'updated_by'];

    public function coa()
    {
        return $this->belongsTo(Coa_model_eloquent::class, 'fin_coa_id', 'fin_coa_id');
    }
}
