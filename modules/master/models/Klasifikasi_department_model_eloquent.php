<?php

namespace Modules\master\models;

defined('BASEPATH') or exit('No direct script access allowed');

use Modules\administration\models\Jabatan_model_eloquent;
use Illuminate\Database\Eloquent\Relations\Pivot as Pivot;
use Modules\master\models\Klasifikasi_department_approval_model_eloquent;


class Klasifikasi_department_model_eloquent extends Pivot
{
    protected $table = 'klasifikasi_department';
    protected $primaryKey = 'klasifikasi_department_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = ['klasifikasi_id', 'department_id', 'type_approval'];

    public static $type_approval = [
        '1' => 'Series',
        '2' => 'Paralel',
    ];

    public function jabatan()
    {
        return $this->hasMany(Klasifikasi_department_approval_model_eloquent::class, 'klasifikasi_department_id', 'klasifikasi_department_id');
    }
}
