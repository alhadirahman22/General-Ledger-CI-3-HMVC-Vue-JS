<?php

namespace Modules\mutasi\models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Mutasi_benda_approval_log_model_eloquent extends Eloquent
{
    protected $table = 'mutasi_benda_approval_log';
    protected $primaryKey = 'mutasi_benda_approval_log_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'mutasi_benda_approval_id',
        'desc',
        'status',
        'created_by',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_mutasi_benda_approval_log_id', 'mutasi_benda_approval_log_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_mutasi_benda_approval_log_id', 'mutasi_benda_approval_log_id');
    }

    public function mutasi_benda_approval()
    {
        return $this->belongsTo(Mutasi_benda_department_approval_model_eloquent::class, 'mutasi_benda_approval_id', 'mutasi_benda_approval_id');
    }
}
