<?php

namespace Modules\administration\models;

use Illuminate\Database\Eloquent\Model as Eloquent;



class LevelJabatan_model_eloquent extends Eloquent
{
    protected $table = 'level_jabatan';
    protected $primaryKey = 'level_jabatan_id';
    public $timestamps = false;
}
