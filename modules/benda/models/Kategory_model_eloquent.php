<?php

namespace Modules\benda\models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class Kategory_model_eloquent extends Eloquent
{
    protected $table = 'kategori';
    protected $primaryKey = 'kategori_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
