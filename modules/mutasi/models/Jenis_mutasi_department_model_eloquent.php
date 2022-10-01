<?php

namespace Modules\mutasi\models;

defined('BASEPATH') or exit('No direct script access allowed');

use Modules\mutasi\models\Jenis_mutasi_model_eloquent;
use Illuminate\Database\Eloquent\Relations\Pivot as Pivot;
use Modules\mutasi\models\Jenis_mutasi_department_approval_model_eloquent;


class Jenis_mutasi_department_model_eloquent extends Pivot
{
    protected $table = 'jenis_mutasi_department';
    protected $primaryKey = 'jenis_mutasi_department_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = ['jenis_mutasi_id', 'department_id', 'type_approval'];

    public static $type_approval = [
        '1' => 'Series',
        '2' => 'Paralel',
    ];

    public function jabatan()
    {
        return $this->hasMany(Jenis_mutasi_department_approval_model_eloquent::class, 'jenis_mutasi_department_id', 'jenis_mutasi_department_id');
    }

    public function jenis_mutasi()
    {
        return $this->belongsTo(Jenis_mutasi_model_eloquent::class, 'jenis_mutasi_id', 'jenis_mutasi_id');
    }
}
