<?php

namespace Modules\finance\models;

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Eloquent\Model as Eloquent;

class SiaModelEloquent extends Eloquent
{
    protected $table = 'sia';
    protected $primaryKey = 'sia_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
