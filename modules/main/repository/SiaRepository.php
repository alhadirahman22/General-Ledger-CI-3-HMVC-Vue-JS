<?php

namespace Modules\main\repository;

use Modules\finance\models\Coa_model_eloquent;
use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\finance\models\Sia_model_eloquent as SIA;
use Modules\finance\models\Coa_saldo_model_eloquent as CoaSaldo;
use Modules\finance\models\Coa_saldo_history_model_eloquent as CoaSaldoHist;

class SiaRepository
{

    protected $CI;
    protected $siaAr;
    private $tbl = 'sia';
    private $tblsaldo = 'fin_coa_saldo';
    private $tblsaldoHistory = 'fin_coa_saldo_history';

    public function __construct($siaAr)
    {
        $this->CI = &get_instance();
        $this->siaAr = $siaAr;
    }

    public function insert()
    {

        try {
            $create = SIA::create($this->siaAr);
        } catch (\Throwable $th) {

            return false;
        }

        return $create->sia_id;
    }

    public function setCoaSaldo($debitCredit = true)
    {
        $insert = $this->insert();

        if (!$insert) {
            return false;
        }
        $siaAr = $this->siaAr;
        $sia_id = $insert;

        try {
            $dataCOA = CoaSaldo::where('fin_coa_id', $siaAr['fin_coa_id'])->first();

            if (!$dataCOA) {
                $dataSaveCoa = [
                    'fin_coa_id' => $siaAr['fin_coa_id'],
                    'value' => 0,
                    'date' => date('Y-m-d'),
                    'created_by' => $siaAr['created_by'],
                ];

                CoaSaldo::create($dataSaveCoa);
            }

            $dataCOA = CoaSaldo::where('fin_coa_id', $siaAr['fin_coa_id'])->first();
            $current_value =   $dataCOA['value'];
            $become_value = 0;
            $type_value = '';
            $value = 0;

            if ($debitCredit) {
                $data_fin_coa = Coa_model_eloquent::find($siaAr['fin_coa_id']);
                if ($data_fin_coa->type == 'D') {
                    if ($siaAr['debit'] > 0) {
                        $type_value = '1'; // plus
                        $become_value = $current_value + $siaAr['debit'];
                        $value = $siaAr['debit'];
                    } else { // credit more than 0
                        $type_value = '2'; // minus
                        $become_value = $current_value - $siaAr['credit'];
                        $value = $siaAr['credit'];
                    }
                } else {
                    if ($siaAr['credit'] > 0) {
                        $type_value = '1'; // plus
                        $become_value = $current_value + $siaAr['credit'];
                        $value = $siaAr['credit'];
                    } else { // credit more than 0
                        $type_value = '2'; // minus
                        $become_value = $current_value - $siaAr['debit'];
                        $value = $siaAr['debit'];
                    }
                }
            } else {
                // add saldo awal
                $type_value = '1'; // plus
                $become_value = $current_value + $siaAr['value'];
                $value = $siaAr['value'];
            }

            $dataSaveCoaHistory = [
                'fin_coa_saldo_id' => $dataCOA['fin_coa_saldo_id'],
                'id_refer' => $siaAr['id_refer'],
                'table_name' => $siaAr['table_name'],
                'desc' => $siaAr['desc'],
                'value' => $value,
                'current_value' => $current_value,
                'become_value' => $become_value,
                'type_value' => $type_value,
                'date_trans' => $siaAr['date_trans'],
                'created_by' => $siaAr['created_by'],
                'id_refer_sub_1' => $siaAr['id_refer_sub_1'],
                'table_name_sub_1' => $siaAr['table_name_sub_1'],
                'id_refer_sub_2' => $siaAr['id_refer_sub_2'],
                'table_name_sub_2' => $siaAr['table_name_sub_2'],
                'sia_id' => $sia_id,

            ];

            CoaSaldoHist::create($dataSaveCoaHistory);
            $updTableSaldo = [
                'value' => $become_value,
                'date' => date('Y-m-d'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            Capsule::table($this->tblsaldo)->where('fin_coa_id', $dataCOA['fin_coa_id'])->update($updTableSaldo);
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }
}
