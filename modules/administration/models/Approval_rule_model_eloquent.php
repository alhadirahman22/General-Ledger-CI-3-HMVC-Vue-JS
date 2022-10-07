<?php

namespace Modules\administration\models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Approval_rule_config_model_eloquent;


class Approval_rule_model_eloquent extends Eloquent
{
    use SoftDeletes;
    protected $table = 'approval_rule';
    protected $primaryKey = 'approval_rule_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public function assign()
    {
        return $this->hasMany(Approval_rule_config_model_eloquent::class, 'approval_rule_id', 'approval_rule_id');
    }

    public function assdept()
    {
        return $this->belongsToMany(Departments_model_eloquent::class, 'approval_rule_department', 'approval_rule_id', 'department_id')
            ->withPivot(['type_approval', 'approval_rule_department_id'])
            ->using(Approval_rule_department_model_eloquent::class);
    }
}
