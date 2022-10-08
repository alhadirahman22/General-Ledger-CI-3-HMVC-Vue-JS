<?php

namespace Modules\reimbursment\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Employee_model_eloquent;


class Reimbursment_dept_approval_emp_model_eloquent extends Eloquent
{
    protected $table = 'approval_rule_department_emp';
    protected $primaryKey = 'reimbursment_dept_approval_emp_id';
    public $timestamps = false;

    protected $fillable = [
        'mutasi_benda_department_approval_id',
        'employee_id',
        'status',
        'condition',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee_model_eloquent::class, 'employee_id', 'employee_id');
    }

    public function log()
    {
        return $this->hasMany(Reimbursment_approval_log_model_eloquent::class, 'reimbursment_approval_log_id', 'reimbursment_approval_log_id');
    }
}
