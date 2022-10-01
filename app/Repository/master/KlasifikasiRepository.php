<?php

namespace Repository\master;

use Modules\master\models\Klasifikasi_model_eloquent;

class KlasifikasiRepository
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

    public function findByID($klasifikasi_id)
    {
        // $dataKlasifikasi = Klasifikasi_model_eloquent::with('tagging_department.pivotJabatan', 'museum')->where('klasifikasi_id', $klasifikasi_id)->first();
        $dataKlasifikasi = Klasifikasi_model_eloquent::with('tagging_department', 'museum')->where('klasifikasi_id', $klasifikasi_id)->first();
        if ($dataKlasifikasi) {
            $tagging_department = $dataKlasifikasi->tagging_department;
            $i = 0;
            $rs = $dataKlasifikasi->toArray();
            foreach ($tagging_department as $td) {

                $rs['tagging_department'][$i]['pivot']['jabatan'] = $td->pivot->jabatan->toArray();
                $i++;
            }
        }

        return $dataKlasifikasi;
    }

    public function get($where = null)
    {
        $data = new Klasifikasi_model_eloquent;
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
