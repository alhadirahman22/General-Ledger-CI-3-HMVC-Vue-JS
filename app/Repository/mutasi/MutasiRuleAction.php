<?php

namespace Repository\mutasi;

use Repository\mutasi\main\MutasiRuleActionInterface;
use Modules\mutasi\models\Mutasi_benda_model_eloquent;

class MutasiRuleAction implements MutasiRuleActionInterface
{

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function createMutasi($id, $jenis_mutasi_id)
    {
        $benda_id  = $id;
        $data = Mutasi_benda_model_eloquent::with(['benda', 'benda_department.mutasi_benda_approval.employee', 'benda_department.department', 'benda_department.mutasi_benda_approval.log'])->where('benda_id', $benda_id)->where('status', '!=', '1')->first();
        if (!$data) {
            $return = array('message' => '', 'status' => 'success');
        } else {
            $return = array('message' => 'Mutasi Benda ' . $data->benda->name . ' is still on progress', 'status' => 'error');
        }

        return $return;
    }
    public function deleteMutasi($id)
    {
        $mutasi_benda_id  = $id;
        $data = Mutasi_benda_model_eloquent::with(['benda_department.mutasi_benda_approval.employee', 'benda_department.department', 'benda_department.mutasi_benda_approval.log'])->where('mutasi_benda_id', $mutasi_benda_id)->where('status', '=', '0')->first();
        if ($data) {
            $return = array('message' => '', 'status' => 'success');
        } else {
            $return = array('message' => 'Mutasi Benda is still on progress', 'status' => 'error');
        }
        return $return;
    }
    public function approveMutasi($id, $department_id, $employee_id) // by type_approval series or paralel
    {

        $mutasi_benda_id = $id;
        $data = Mutasi_benda_model_eloquent::with(['benda_department.mutasi_benda_approval.employee', 'benda_department.department', 'benda_department.mutasi_benda_approval.log'])->where('mutasi_benda_id', $mutasi_benda_id)->where('status', '!=', '1')->first();

        if ($data) {
            $data = $data->toArray();
            $benda_department = $data['benda_department'];
            $bool = false;
            for ($i = 0; $i < count($benda_department); $i++) {
                $condition = $benda_department[$i]['condition'];
                $department_idData = $benda_department[$i]['department_id'];
                if ($department_idData == $department_id && $condition == '1') {
                    $mutasi_benda_approval =  $benda_department[$i]['mutasi_benda_approval'];
                    for ($j = 0; $j < count($mutasi_benda_approval); $j++) {
                        $employee_idApproval = $mutasi_benda_approval[$j]['employee_id'];
                        if ($employee_idApproval == $employee_id) {
                            $bool = true;
                            break;
                        }
                    }
                }

                if ($bool) {
                    break;
                }
            }

            if ($bool) {
                $return = array('message' => '', 'status' => 'success');
            } else {
                $return = array('message' => 'You are not allowed to approval', 'status' => 'error');
            }
        } else {
            $return = array('message' => 'You are not allowed to approval', 'status' => 'error');
        }

        return $return;
    }
    public function editMutasi($id)
    {
        /* no need, because edit not allowed
        $benda_id  = $id;
        $data = Mutasi_benda_model_eloquent::with(['benda_department.mutasi_benda_approval.employee', 'benda_department.department', 'benda_department.mutasi_benda_approval.log'])->where('benda_id', $benda_id)->where('status', '=', '0')->first();
        if ($data) {
            $return = array('message' => '', 'status' => 'success');
        } else {
            $return = array('message' => 'Mutasi Benda is still on progress', 'status' => 'error');
        }
        return $return;
        */
    }
}
