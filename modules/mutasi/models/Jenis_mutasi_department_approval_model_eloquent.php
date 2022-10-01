<?php

namespace Modules\mutasi\models;

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Relations\Pivot as Pivot;

class Jenis_mutasi_department_approval_model_eloquent extends Pivot
{
    protected $table = 'jenis_mutasi_department_approval';
    protected $primaryKey = 'jenis_mutasi_department_approval_id';
    public $timestamps = false;
    protected $fillable = ['jenis_mutasi_department_id', 'jabatan_id'];
}
