<?php

namespace Modules\mutasi\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Employee_model_eloquent;
use Modules\mutasi\models\Mutasi_benda_department_approval_model_eloquent;

class Mutasi_benda_approval_model_eloquent extends Eloquent
{
    protected $table = 'mutasi_benda_approval';
    protected $primaryKey = 'mutasi_benda_approval_id';

    protected $fillable = [
        'mutasi_benda_department_approval_id',
        'employee_id',
        'status',
        'condition',
    ];
    public $timestamps = false;

    public function mutasi_benda_department()
    {
        return $this->belongsTo(Mutasi_benda_department_approval_model_eloquent::class, 'mutasi_benda_department_approval_id', 'mutasi_benda_department_approval_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee_model_eloquent::class, 'employee_id', 'employee_id');
    }

    public function log()
    {
        return $this->hasMany(Mutasi_benda_approval_log_model_eloquent::class, 'mutasi_benda_approval_id', 'mutasi_benda_approval_id');
    }
}
