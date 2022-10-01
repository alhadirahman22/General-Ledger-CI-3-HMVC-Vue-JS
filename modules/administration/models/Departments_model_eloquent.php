<?php

namespace Modules\administration\models;

use Modules\benda\models\Benda_model_eloquent;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\mutasi\models\Jenis_mutasi_model_eloquent;
use Modules\administration\models\Museum_model_eloquent;
use Modules\administration\models\Jabatan_model_eloquent;
use Modules\administration\models\Employee_model_eloquent;
use Modules\master\models\Klasifikasi_department_model_eloquent;
use Modules\mutasi\models\Jenis_mutasi_department_model_eloquent;
use Modules\mutasi\models\Jenis_mutasi_department_approval_model_eloquent;



class Departments_model_eloquent extends Eloquent
{
    protected $table = 'departments';
    protected $primaryKey = 'department_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public function museum()
    {
        return $this->belongsTo(Museum_model_eloquent::class, 'museum_id', 'museum_id');
    }

    public function tagging_klasifikasi()
    {
        return $this->belongsToMany(Jenis_mutasi_model_eloquent::class, 'jenis_mutasi_department', 'department_id', 'jenis_mutasi_id')
            ->withPivot(['type_approval', 'jenis_mutasi_department_id'])
            ->using(Jenis_mutasi_department_model_eloquent::class);
    }

    public function employee()
    {
        return $this->belongsToMany(Employee_model_eloquent::class, 'jabatan_department_employee', 'department_id', 'employee_id');
    }

    public function jabatan()
    {
        return $this->belongsToMany(Jabatan_model_eloquent::class, 'jabatan_department_employee', 'department_id', 'jabatan_id');
    }

    public function pivotJabatan()
    {
        return $this->hasManyThrough(Jenis_mutasi_department_approval_model_eloquent::class, Jenis_mutasi_department_model_eloquent::class, 'department_id', 'jenis_mutasi_department_id');
    }

    public function benda()
    {
        return $this->hasMany(Benda_model_eloquent::class, 'department_id', 'department_id');
    }
}
