<?php

namespace Modules\dashboard\models;

use Modules\benda\models\Tagging_model_eloquent;
use Illuminate\Database\Eloquent\Model as Eloquent;


class DashboardStatsBendaTagging_model_eloquent extends Eloquent
{
    protected $table = 'dashboard_stats_benda_tagging';
    protected $primaryKey = 'dashboard_stats_id';
    public $timestamps = false;

    public function tagging()
    {
        return $this->belongsTo(Tagging_model_eloquent::class, 'tag_id', 'tag_id');
    }
}
