<?php

namespace Modules\mutasi\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\mutasi\models\Mutasi_benda_model_eloquent;
use Modules\administration\models\Departments_model_eloquent;
use Modules\mutasi\models\Mutasi_benda_approval_model_eloquent;

class Mutasi_benda_department_approval_model_eloquent extends Eloquent
{
    protected $table = 'mutasi_benda_department_approval';
    protected $primaryKey = 'mutasi_benda_department_approval_id';
    public $timestamps = false;

    protected $fillable = [
        'mutasi_benda_id',
        'department_id',
        'type_approval',
        'status',
        'condition',
    ];

    public function mutasi_benda()
    {
        return $this->belongsTo(Mutasi_benda_model_eloquent::class, 'mutasi_benda_id', 'mutasi_benda_id');
    }

    public function department()
    {
        return $this->belongsTo(Departments_model_eloquent::class, 'department_id', 'department_id');
    }

    public function mutasi_benda_approval()
    {
        return $this->hasMany(Mutasi_benda_approval_model_eloquent::class, 'mutasi_benda_department_approval_id', 'mutasi_benda_department_approval_id');
    }
}
