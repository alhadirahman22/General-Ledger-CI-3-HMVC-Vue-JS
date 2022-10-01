<?php

namespace Modules\dashboard\models;

use Modules\benda\models\Kategory_model_eloquent;
use Illuminate\Database\Eloquent\Model as Eloquent;


class DashboardStatsCategoryBenda_model_eloquent extends Eloquent
{
    protected $table = 'dashboard_stats_category_benda';
    protected $primaryKey = 'dashboard_stats_category_benda_id';
    public $timestamps = false;

    public function kategori()
    {
        return $this->belongsTo(Kategory_model_eloquent::class, 'kategory_id', 'kategori_id');
    }
}
