<?php

namespace Modules\mutasi\models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\benda\models\Benda_model_eloquent;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Modules\administration\models\Employee_model_eloquent;
use Modules\mutasi\models\Mutasi_benda_department_approval_model_eloquent;

class Mutasi_benda_model_eloquent extends Eloquent
{

    use SoftDeletes;
    protected $table = 'mutasi_benda';
    protected $primaryKey = 'mutasi_benda_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function benda()
    {
        return $this->belongsTo(Benda_model_eloquent::class, 'benda_id', 'benda_id');
    }

    public function requested()
    {
        return $this->belongsTo(Employee_model_eloquent::class, 'employee_id', 'requested_by');
    }

    public function benda_department()
    {
        return $this->hasMany(Mutasi_benda_department_approval_model_eloquent::class, 'mutasi_benda_id', 'mutasi_benda_id');
    }

    public function jenis_mutasi()
    {
        return $this->belongsTo(Jenis_mutasi_model_eloquent::class, 'jenis_mutasi_id', 'jenis_mutasi_id');
    }
}
