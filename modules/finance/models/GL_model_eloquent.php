<?php

namespace Modules\finance\models;

defined('BASEPATH') or exit('No direct script access allowed');

use Modules\finance\models\Coa_model_eloquent;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\finance\models\Gl_detail_model_eloquent;
use Modules\finance\models\Jurnal_voucher_model_eloquent;


class GL_model_eloquent extends Eloquent
{
    protected $table = 'fin_gl';
    protected $primaryKey = 'fin_gl_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'fin_jurnal_voucher_id',
        'fin_gl_prefix',
        'fin_gl_code',
        'fin_gl_date',
        'fin_gl_code_inc',
        'fin_gl_no_bukti',
        'debit_total',
        'credit_total',
        'selisih_total',
        'created_by',
        'updated_by',
        'status',
    ];

    public function jurnal()
    {
        return $this->belongsTo(Jurnal_voucher_model_eloquent::class, 'fin_jurnal_voucher_id', 'fin_jurnal_voucher_id');
    }

    public function detail_taging_coa()
    {
        return $this->belongsToMany(Coa_model_eloquent::class, 'fin_gl_detail', 'fin_gl_id', 'fin_coa_id')
            ->withPivot(['fin_gl_detail_id', 'fin_gl_referensi', 'debit', 'credit', 'desc'])
            ->using(Gl_detail_model_eloquent::class);
    }

    public function detail()
    {
        return $this->hasMany(Gl_detail_model_eloquent::class, 'fin_gl_id', 'fin_gl_id');
    }
}
