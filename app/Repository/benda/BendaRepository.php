<?php

namespace Repository\benda;

use Modules\benda\models\Benda_model_eloquent;

class BendaRepository
{
    protected $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function findByID($id)
    {
        $benda_id = $id;
        $dataBenda = Benda_model_eloquent::find($benda_id);

        return $dataBenda;
    }

    public function get($where = null)
    {
        $data = new Benda_model_eloquent;
        $data = $data->with('klasifikasi', 'department');
        if ($where != null) {
            foreach ($where as $key => $value) {
                $data = $data->where($key, $value);
            }
            return $data->get()->toArray();
        } else {
            return $data->get()->toArray();
        }
    }
}
