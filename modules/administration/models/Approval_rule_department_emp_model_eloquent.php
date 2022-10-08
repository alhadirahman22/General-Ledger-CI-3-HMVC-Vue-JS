<?php

namespace Modules\administration\models;


use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Employee_model_eloquent;


class Approval_rule_department_emp_model_eloquent extends Eloquent
{
    protected $table = 'approval_rule_department_emp';
    protected $primaryKey = 'approval_rule_department_emp_id';
    protected $fillable = ['approval_rule_department_id', 'jabatan_id'];

    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(Employee_model_eloquent::class, 'employee_id', 'employee_id');
    }
}
