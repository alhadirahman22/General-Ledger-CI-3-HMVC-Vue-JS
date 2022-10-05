<?php

namespace Modules\administration\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Company_model_eloquent;
use Modules\administration\models\Jabatan_model_eloquent;
use Modules\administration\models\Employee_model_eloquent;



class Departments_model_eloquent extends Eloquent
{
    protected $table = 'departments';
    protected $primaryKey = 'department_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public function company()
    {
        return $this->belongsTo(Company_model_eloquent::class, 'company_id', 'company_id');
    }

    public function employee()
    {
        return $this->belongsToMany(Employee_model_eloquent::class, 'jabatan_department_employee', 'department_id', 'employee_id');
    }

    public function jabatan()
    {
        return $this->belongsToMany(Jabatan_model_eloquent::class, 'jabatan_department_employee', 'department_id', 'jabatan_id');
    }
}
