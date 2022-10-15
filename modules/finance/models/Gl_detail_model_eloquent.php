<?php

namespace Modules\finance\models;

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Relations\Pivot as Pivot;


class Gl_detail_model_eloquent extends Pivot
{
    protected $table = 'fin_gl_detail';
    protected $primaryKey = 'fin_gl_detail_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = ['fin_gl_id', 'fin_coa_id', 'fin_gl_referensi', 'debit', 'credit', 'desc', 'created_by', 'updated_by'];
}
