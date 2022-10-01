<?php

namespace Modules\dashboard\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\dashboard\models\DashboardStatsBendaTagging_model_eloquent;
use Modules\dashboard\models\DashboardStatsCategoryBenda_model_eloquent;
use Modules\dashboard\models\DashboardStatsDepartmentBenda_model_eloquent;


class DashboardStats_model_eloquent extends Eloquent
{
    protected $table = 'dashboard_stats';
    protected $primaryKey = 'dashboard_stats_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'benda_is_good',
        'benda_is_bad',
        'benda_is_all',
        'pengunjung',
        'mutasi_process',
        'ram_used',
        'ram_total',
        'benda_last3month',
        'all_user',
    ];

    public function benda_category()
    {
        return $this->hasMany(DashboardStatsCategoryBenda_model_eloquent::class, 'dashboard_stats_id', 'dashboard_stats_id');
    }

    public function benda_department()
    {
        return $this->hasMany(DashboardStatsDepartmentBenda_model_eloquent::class, 'dashboard_stats_id', 'dashboard_stats_id');
    }

    public function benda_tagging()
    {
        return $this->hasMany(DashboardStatsBendaTagging_model_eloquent::class, 'dashboard_stats_id', 'dashboard_stats_id');
    }
}
