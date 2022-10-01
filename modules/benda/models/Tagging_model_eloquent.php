<?php

namespace Modules\benda\models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class Tagging_model_eloquent extends Eloquent
{
    protected $table = 'tags';
    protected $primaryKey = 'tag_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
