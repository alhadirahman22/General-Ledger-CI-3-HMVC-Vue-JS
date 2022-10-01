<?php

namespace Repository\mutasi;

use Modules\mutasi\models\Jenis_mutasi_model_eloquent;


class JenisMutasiRepository
{
    protected $CI;
    public $type_approval = [
        '1' => 'Series',
        '2' => 'Paralel',
    ];
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function findByID($jenis_mutasi_id)
    {
        $dataJenisMutasi = Jenis_mutasi_model_eloquent::with('tagging_department', 'museum')->where('jenis_mutasi_id', $jenis_mutasi_id)->first();
        if ($dataJenisMutasi) {
            $tagging_department = $dataJenisMutasi->tagging_department;
            $i = 0;
            $rs = $dataJenisMutasi->toArray();
            foreach ($tagging_department as $td) {

                $rs['tagging_department'][$i]['pivot']['jabatan'] = $td->pivot->jabatan->toArray();
                $i++;
            }
        }

        return $dataJenisMutasi;
    }

    public function get($where = null)
    {
        $data = new Jenis_mutasi_model_eloquent;
        $data = $data->with('tagging_department', 'museum');
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
