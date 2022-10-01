<?php

namespace Modules\master\models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Museum_model_eloquent;
use Modules\administration\models\Jabatan_model_eloquent;
use Modules\administration\models\Departments_model_eloquent;
use Modules\master\models\Klasifikasi_department_model_eloquent;


class Klasifikasi_model_eloquent extends Eloquent
{
    protected $table = 'klasifikasi';
    protected $primaryKey = 'klasifikasi_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public static $type_approval = [
        '1' => 'Series',
        '2' => 'Paralel',
    ];

    public function tagging_department()
    {
        return $this->belongsToMany(Departments_model_eloquent::class, 'klasifikasi_department', 'klasifikasi_id', 'department_id')
            ->withPivot(['type_approval', 'klasifikasi_department_id'])
            ->using(Klasifikasi_department_model_eloquent::class);
    }

    public function museum()
    {
        return $this->belongsTo(Museum_model_eloquent::class, 'museum_id', 'museum_id');
    }
}
