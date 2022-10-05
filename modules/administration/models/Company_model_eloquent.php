<?php

namespace Modules\administration\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Departments_model_eloquent;
use Modules\administration\models\Jabatan_department_employee_model_eloquent;


class Company_model_eloquent extends Eloquent
{
    protected $table = 'company';
    protected $primaryKey = 'company_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function departments()
    {
        return $this->hasMany(Departments_model_eloquent::class, 'company_id', 'company_id');
    }

    public function getEmployee()
    {
        return $this->hasMany(Jabatan_department_employee_model_eloquent::class, 'company_id', 'company_id');
    }
}
