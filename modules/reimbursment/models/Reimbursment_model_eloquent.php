<?php

namespace Modules\reimbursment\models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Employee_model_eloquent;
use Modules\reimbursment\models\Reimbursment_model_eloquent;

class Reimbursment_model_eloquent extends Eloquent
{
    use SoftDeletes;
    protected $table = 'reimbursment';
    protected $primaryKey = 'reimbursment_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function requested()
    {
        return $this->belongsTo(Employee_model_eloquent::class, 'employee_id', 'requested_by');
    }

    public function reimbursment_department()
    {
        return $this->hasMany(Reimbursment_dept_approval_model_eloquent::class, 'reimbursment_id', 'reimbursment_id');
    }
}
