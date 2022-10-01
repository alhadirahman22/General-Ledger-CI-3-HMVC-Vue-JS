<?php

namespace Modules\mutasi\models;

use Museum;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Museum_model_eloquent;
use Modules\administration\models\Departments_model_eloquent;
use Modules\mutasi\models\Jenis_mutasi_department_model_eloquent;



class Jenis_mutasi_model_eloquent extends Eloquent
{

    use SoftDeletes;

    protected $table = 'jenis_mutasi';
    protected $primaryKey = 'jenis_mutasi_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public static $type_approval = [
        '1' => 'Series',
        '2' => 'Paralel',
    ];

    public function tagging_department()
    {
        return $this->belongsToMany(Departments_model_eloquent::class, 'jenis_mutasi_department', 'jenis_mutasi_id', 'department_id')
            ->withPivot(['type_approval', 'jenis_mutasi_department_id'])
            ->using(Jenis_mutasi_department_model_eloquent::class);
    }

    public function museum()
    {
        return $this->belongsTo(Museum_model_eloquent::class, 'museum_id', 'museum_id');
    }
}
