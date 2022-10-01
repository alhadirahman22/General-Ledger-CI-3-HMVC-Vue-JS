<?php

namespace Modules\master\models;

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Relations\Pivot as Pivot;


class Klasifikasi_department_approval_model_eloquent extends Pivot
{
    protected $table = 'klasifikasi_department_approval';
    protected $primaryKey = 'klasifikasi_department_approval_id';
    public $timestamps = false;
    protected $fillable = ['klasifikasi_department_id', 'jabatan_id'];
}
