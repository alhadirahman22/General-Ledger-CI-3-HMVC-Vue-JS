<?php

namespace Modules\administration\models;


use Illuminate\Database\Eloquent\Model as Eloquent;


class Approval_rule_config_model_eloquent extends Eloquent
{
    protected $table = 'approval_rule_config';
    protected $primaryKey = 'approval_rule_config_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
