<?php

namespace Modules\administration\models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Company_model_eloquent;
use Modules\administration\models\Employee_model_eloquent;
use Modules\administration\models\Departments_model_eloquent;
use Modules\administration\models\LevelJabatan_model_eloquent;


class Jabatan_model_eloquent extends Eloquent
{

    use SoftDeletes;

    protected $table = 'jabatan';
    protected $primaryKey = 'jabatan_id';
    public $timestamps = false;

    public function employee()
    {
        return $this->belongsToMany(Employee_model_eloquent::class, 'jabatan_department_employee', 'jabatan_id', 'employee_id');
    }

    public function department()
    {
        return $this->belongsToMany(Departments_model_eloquent::class, 'jabatan_department_employee', 'jabatan_id', 'department_id');
    }


    public function getDepartment()
    {
        return $this->belongsTo(Departments_model_eloquent::class, 'department_id', 'department_id');
    }

    public function getLevel()
    {
        return $this->belongsTo(LevelJabatan_model_eloquent::class, 'level_jabatan_id', 'level_jabatan_id');
    }

    public function company()
    {
        return $this->belongsTo(Company_model_eloquent::class, 'company_id', 'company_id');
    }
}
