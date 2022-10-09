<?php

namespace Modules\reimbursment\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\reimbursment\models\Reimbursment_dept_approval_emp_model_eloquent;

class Reimbursment_approval_log_model_eloquent extends Eloquent
{
    protected $table = 'reimbursment_approval_log';
    protected $primaryKey = 'reimbursment_approval_log_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'reimbursment_dept_approval_emp_id',
        'desc',
        'status',
        'created_by',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_reimbursment_approval_log_id', 'reimbursment_approval_log_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_reimbursment_approval_log_id', 'reimbursment_approval_log_id');
    }

    public function emp_approval()
    {
        return $this->belongsTo(Reimbursment_dept_approval_emp_model_eloquent::class, 'reimbursment_dept_approval_emp_id', 'reimbursment_dept_approval_emp_id');
    }
}
