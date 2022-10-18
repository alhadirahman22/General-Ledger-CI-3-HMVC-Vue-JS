<?php

namespace Modules\main\repository;


use Illuminate\Database\Capsule\Manager as Capsule;

class MainRepository
{

    protected $CI;
    protected $arrCodeEloquent;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->arrCodeEloquent = [
            [
                'namespace' =>  "Modules\\reimbursment\\models\Reimbursment_model_eloquent",
                'code' => 'code',
                'available' => [
                    [
                        'field' => 'status',
                        'value' => '1',
                    ]

                ]
            ]


        ];
    }

    public function searchAllCode($query)
    {
        $result = [];
        $arrCodeEloquent = $this->arrCodeEloquent;
        for ($i = 0; $i < count($arrCodeEloquent); $i++) {
            $namespace =  $arrCodeEloquent[$i]['namespace'];
            $data = new $namespace;
            // $data = $data->where($arrCodeEloquent[$i]['code'], 'like', '' . $query . '%')->get()->toArray();
            $data = $data->where($arrCodeEloquent[$i]['code'], 'like', '' . $query . '%');
            if ($arrCodeEloquent[$i]['available']) {
                $available = $arrCodeEloquent[$i]['available'];

                for ($k = 0; $k < count($available); $k++) {
                    $field = $available[$k]['field'];
                    $value = $available[$k]['value'];

                    $data = $data->where($field, $value);
                }
            }
            $data = $data->get()->toArray();
            for ($j = 0; $j < count($data); $j++) {
                $result[] = [
                    'text' => $data[$j][$arrCodeEloquent[$i]['code']],
                    'code' => $data[$j][$arrCodeEloquent[$i]['code']],
                ];
            }
        }

        return $result;
    }

    public function option($table, $id, $text)
    {
        $data = $this->CI->db->get($table)->result_array();
        $op = [];
        $ex = explode('-', $text);
        for ($i = 0; $i < count($data); $i++) {
            // $textshow = count($ex) > 0 ?  $data[$i][$ex[0]] . ' - ' . $data[$i][$ex[1]] : $data[$i][$text];

            if (count($ex) > 0) {
                $textshow = $data[$i][$ex[0]];
                for ($k = 1; $k < count($ex); $k++) {
                    $textshow .= ' - ' . $data[$i][$ex[$k]];
                }
            } else {
                $textshow = $data[$i][$text];
            }

            $temp = ['id' => $data[$i][$id], 'text' => $textshow];

            $op[] = $temp;
        }

        return $op;
    }

    public function optionModels($eloquent, $id, $text)
    {
        $datas = new $eloquent;
        $datas = $datas->get()->toArray();
        $ex = explode('-', $text);

        $op = [];
        for ($i = 0; $i < count($datas); $i++) {
            // $textshow = count($ex) > 0 ?  $datas[$i][$ex[0]] . ' - ' . $datas[$i][$ex[1]] : $datas[$i][$text];

            if (count($ex) > 0) {
                $textshow = $datas[$i][$ex[0]];
                for ($k = 1; $k < count($ex); $k++) {
                    $textshow .= ' - ' . $datas[$i][$ex[$k]];
                }
            } else {
                $textshow = $datas[$i][$text];
            }
            $temp = ['id' => $datas[$i][$id], 'text' => $textshow];

            $op[] = $temp;
        }

        return $op;
    }
}
