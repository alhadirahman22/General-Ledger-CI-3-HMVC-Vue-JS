<?php

namespace Modules\administration\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Jabatan_model_eloquent;
use Modules\administration\models\Departments_model_eloquent;



class Employee_model_eloquent extends Eloquent
{
    protected $table = 'employees';
    protected $primaryKey = 'employee_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function department()
    {
        return $this->belongsToMany(Departments_model_eloquent::class, 'jabatan_department_employee', 'employee_id', 'department_id')
            ->withPivot(['department_id', 'jabatan_id', 'jabatan_department_id']);
    }

    public function jabatan()
    {
        return $this->belongsToMany(Jabatan_model_eloquent::class, 'jabatan_department_employee', 'employee_id', 'jabatan_id')
            ->withPivot(['department_id', 'jabatan_id', 'jabatan_department_id']);
    }
}
