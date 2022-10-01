<?php

namespace Modules\benda\models;

use Modules\benda\models\Kategory_model_eloquent;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\master\models\Klasifikasi_model_eloquent;
use Modules\mutasi\models\Mutasi_benda_model_eloquent;
use Modules\administration\models\Departments_model_eloquent;

class Benda_model_eloquent extends Eloquent
{
    protected $table = 'bendas';
    protected $primaryKey = 'benda_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public function mutasi()
    {
        return $this->hasMany(Mutasi_benda_model_eloquent::class, 'benda_id', 'benda_id');
    }

    public function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi_model_eloquent::class, 'klasifikasi_id', 'klasifikasi_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategory_model_eloquent::class, 'kategori_id', 'kategori_id');
    }

    public function department()
    {
        return $this->belongsTo(Departments_model_eloquent::class, 'department_id', 'department_id');
    }
}
