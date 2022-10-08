<?php

namespace Modules\administration\models;


use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Departments_model_eloquent;
use Modules\administration\models\Approval_rule_model_eloquent;
use Modules\administration\models\Approval_rule_department_emp_model_eloquent;


class Approval_rule_department_model_eloquent2 extends Eloquent
{
    protected $table = 'approval_rule_department';
    protected $primaryKey = 'approval_rule_department_id';
    protected $fillable = ['approval_rule_id', 'department_id', 'type_approval'];

    public $timestamps = false;

    public function jabatan()
    {
        return $this->hasMany(Approval_rule_department_emp_model_eloquent::class, 'approval_rule_department_id', 'approval_rule_department_id');
    }

    public function department()
    {
        return $this->belongsTo(Departments_model_eloquent::class, 'department_id', 'department_id');
    }


    public function approval_name()
    {
        return $this->belongsTo(Approval_rule_model_eloquent::class, 'approval_rule_id', 'approval_rule_id');
    }
}
