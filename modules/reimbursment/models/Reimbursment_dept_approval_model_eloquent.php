<?php

namespace Modules\reimbursment\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Departments_model_eloquent;


class Reimbursment_dept_approval_model_eloquent extends Eloquent
{
    protected $table = 'reimbursment_dept_approval';
    protected $primaryKey = 'reimbursment_dept_approval_id';
    public $timestamps = false;

    protected $fillable = [
        'reimbursment_id',
        'department_id',
        'type_approval',
        'status',
        'condition',
    ];

    public function department()
    {
        return $this->belongsTo(Departments_model_eloquent::class, 'department_id', 'department_id');
    }

    public function approval()
    {
        return $this->hasMany(Reimbursment_dept_approval_emp_model_eloquent::class, 'reimbursment_dept_approval_id', 'reimbursment_dept_approval_id');
    }
}
