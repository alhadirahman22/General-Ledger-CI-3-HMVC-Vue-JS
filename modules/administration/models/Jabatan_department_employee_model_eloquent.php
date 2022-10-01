<?php

namespace Modules\administration\models;

use Illuminate\Database\Eloquent\Relations\Pivot as Pivot;
use Modules\administration\models\Employee_model_eloquent;

class Jabatan_department_employee_model_eloquent extends Pivot
{

    protected $table = 'jabatan_department_employee';
    protected $primaryKey = 'jabatan_department_employee_id';
    public $timestamps = false;

    public function emp()
    {
        return $this->belongsTo(Employee_model_eloquent::class, 'employee_id', 'employee_id');
    }
}
