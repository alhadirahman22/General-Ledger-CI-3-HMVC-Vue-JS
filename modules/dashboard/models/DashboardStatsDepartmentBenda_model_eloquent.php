<?php

namespace Modules\dashboard\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Departments_model_eloquent;


class DashboardStatsDepartmentBenda_model_eloquent extends Eloquent
{
    protected $table = 'dashboard_stats_department_benda';
    protected $primaryKey = 'dashboard_stats_department_benda_id';
    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo(Departments_model_eloquent::class, 'department_id', 'department_id');
    }
}
