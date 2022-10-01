<?php

namespace Repository\mutasi;

use Repository\mutasi\MutasiRepository;
use Repository\mutasi\MutasiLogRepository;
use Modules\benda\models\Benda_model_eloquent;
use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\mutasi\models\Mutasi_benda_model_eloquent;
use Repository\mutasi\main\MutasiRepositoryStatusInterface;
use Modules\administration\models\Departments_model_eloquent;
use Modules\mutasi\models\Mutasi_benda_approval_model_eloquent;
use Modules\mutasi\models\Mutasi_benda_department_approval_model_eloquent;

class MutasiRepositoryStatus implements MutasiRepositoryStatusInterface
{
    protected $CI;
    protected $MutasiRepository;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function updateStatusFirst($mutasi_benda_id, $status = '2')
    {
        $dataMutasiBenda = Mutasi_benda_model_eloquent::find($mutasi_benda_id);
        // $dataMutasiBenda->status = '2';
        $dataMutasiBenda->status = $status;
        $dataMutasiBenda->save();
    }
    public function updateAfterApproval($mutasi_benda_id)
    {
        Capsule::beginTransaction();
        try {
            $this->updateStatusFirst($mutasi_benda_id);

            $statusMain = MutasiRepositoryStatusInterface::statusData;
            $statusLog = $statusMain['1'];
            $MutasiRepository = new MutasiRepository();
            $data = $MutasiRepository->findByID($mutasi_benda_id)->toArray();
            $benda_department = $data['benda_department'];
            $AllEmpApproved = false;
            $findIndexDeptEmployeeApproved = $this->findIndexDeptEmployeeApproved($benda_department);


            if ($findIndexDeptEmployeeApproved !== null) {
                $dataDeptApproved = $benda_department[$findIndexDeptEmployeeApproved]['mutasi_benda_approval'];

                // update progress department
                $updDep = Mutasi_benda_department_approval_model_eloquent::find($benda_department[$findIndexDeptEmployeeApproved]['mutasi_benda_department_approval_id']);
                $updDep->status = '2';
                $updDep->save();

                $findIndexEmpApproved = $this->findIndexEmpApproved($dataDeptApproved);
                if ($findIndexEmpApproved !== null) {
                    $dataEmpApproved = $dataDeptApproved[$findIndexEmpApproved];

                    $dataSavedLog = [
                        'mutasi_benda_approval_id' => $dataEmpApproved['mutasi_benda_approval_id'],
                        'desc' => $statusLog,
                        'status' => $dataEmpApproved['status'],
                        'created_by' =>  $this->CI->data['user']->id,
                    ];

                    $MutasiLogRepository = new MutasiLogRepository();
                    $MutasiLogRepository->insertLog($dataSavedLog);

                    $upd = Mutasi_benda_approval_model_eloquent::find($dataEmpApproved['mutasi_benda_approval_id']);
                    $upd->condition = '2';
                    $upd->save();
                }

                $nextApprovalEmp = $this->nextApprovalEmp($dataEmpApproved, $findIndexEmpApproved);
                if ($nextApprovalEmp) {
                    $dataEmpApprovedNext = $dataDeptApproved[$nextApprovalEmp];
                    $mutasi_benda_approval_id = $dataEmpApprovedNext['mutasi_benda_approval_id'];
                    $upd = Mutasi_benda_approval_model_eloquent::find($mutasi_benda_approval_id);
                    $upd->condition =  '1';
                    $upd->status =  '0';
                    $upd->save();
                } else {
                    $AllEmpApproved = true;
                    $mutasi_benda_department_approval_id = $dataDeptApproved[0]['mutasi_benda_department_approval_id'];
                    $dataDep = Mutasi_benda_department_approval_model_eloquent::find($mutasi_benda_department_approval_id);
                    $dataDep->status = '1';
                    $dataDep->condition = '2';
                    $dataDep->save();
                }
            }

            if ($AllEmpApproved) {
                $nextApproveDept = $this->nextApproveDept($benda_department, $findIndexDeptEmployeeApproved);

                if ($nextApproveDept) {
                    $dataDeptNext = $benda_department[$nextApproveDept];
                    $mutasi_benda_department_approval_id = $dataDeptNext['mutasi_benda_department_approval_id'];
                    $dataDep = Mutasi_benda_department_approval_model_eloquent::find($mutasi_benda_department_approval_id);
                    $dataDep->status = '0';
                    $dataDep->condition = '1';
                    $dataDep->save();

                    // set emp
                    $mutasi_benda_approval_id = $dataDeptNext['mutasi_benda_approval'][0]['mutasi_benda_approval_id'];
                    $upd = Mutasi_benda_approval_model_eloquent::find($mutasi_benda_approval_id);
                    $upd->condition =  '1';
                    $upd->status =  '0';
                    $upd->save();
                } else {
                    // all is done
                    $check =  $this->updateStatusWhenIsDone($mutasi_benda_id);
                    if (!$check) {
                        Capsule::rollback();
                        return false;
                    }
                }
            }

            Capsule::commit();
            return true;
        } catch (\Throwable $th) {
            Capsule::rollback();
            return false;
        }


        return false;
    }

    private function nextApproveDept($benda_department, $findIndexDeptEmployeeApproved)
    {
        $nextIndex = $findIndexDeptEmployeeApproved + 1;
        if (isset($benda_department[$nextIndex])) {
            return $nextIndex;
        }


        return false;
    }

    private function nextApprovalEmp($dataEmpApproved, $findIndexEmpApproved)
    {
        $nextIndex = $findIndexEmpApproved + 1;
        if (isset($dataEmpApproved[$nextIndex])) {
            return $nextIndex;
        }

        return false;
    }

    private function findIndexEmpApproved($dataDeptApproved)
    {
        $index = null;
        for ($i = 0; $i < count($dataDeptApproved); $i++) {
            $findCondition = '1';
            if ($findCondition  == $dataDeptApproved[$i]['condition']) {
                $index = $i;
                break;
            }
        }

        return $index;
    }

    private function findIndexDeptEmployeeApproved($benda_department)
    {
        $index = null;
        for ($i = 0; $i < count($benda_department); $i++) {
            $findCondition = '1';
            if ($findCondition  == $benda_department[$i]['condition']) {
                $index = $i;
                break;
            }
        }

        return $index;
    }


    public function findConditionRejected($data)
    {
        $rs = '';
        $rejectStatus = '-1';
        $benda_department = $data['benda_department'];

        for ($i = 0; $i < count($benda_department); $i++) {
            $department_id = $benda_department[$i]['department_id'];
            $statusDepartment = $benda_department[$i]['status'];
            if ($statusDepartment == $rejectStatus) {
                $endWorld = ' On ' . $benda_department[$i]['department']['name'] . ' Department';
                $mutasi_benda_approval = $benda_department[$i]['mutasi_benda_approval'];
                for ($j = 0; $j < count($mutasi_benda_approval); $j++) {
                    $statusEmp = $mutasi_benda_approval[$j]['status'];
                    if ($statusEmp == $rejectStatus) {
                        $rs .= '<li>' . $mutasi_benda_approval[$j]['employee']['nip'] . ' - ' . $mutasi_benda_approval[$j]['employee']['name'] . '</li>';
                    }
                }
                $rs .= '<br/>' . $endWorld;
            }
        }

        return $rs;
    }

    public function findCondition1($data)
    {
        $status = MutasiRepositoryStatusInterface::statusData;
        $condition = MutasiRepositoryStatusInterface::condition;
        $rs = '';
        $benda_department = $data['benda_department'];

        for ($i = 0; $i < count($benda_department); $i++) {
            $department_id = $benda_department[$i]['department_id'];
            $condition = $benda_department[$i]['condition'];
            if ($condition == '1') {
                $statusDepartment = $benda_department[$i]['status'];
                $departmentData = Departments_model_eloquent::find($department_id);
                if ($statusDepartment == '0') {
                    $endWorld = ' On ' . $departmentData->name . ' Department';
                    $mutasi_benda_approval = $benda_department[$i]['mutasi_benda_approval'];
                    for ($j = 0; $j < count($mutasi_benda_approval); $j++) {
                        $conditionEmp = $mutasi_benda_approval[$j]['condition'];
                        $statusEmp = $mutasi_benda_approval[$j]['status'];
                        if ($conditionEmp == '1') {
                            // if ($j > 0) {
                            //     $rs .= ' ,';
                            // }
                            $rs .= '<li>' . $status[$statusEmp] . ' ' . $mutasi_benda_approval[$j]['employee']['nip'] . ' - ' . $mutasi_benda_approval[$j]['employee']['name'] . '</li>';
                        }
                    }

                    $rs .= '<br/>' . $endWorld;
                }
            }
        }

        return $rs;
    }
    public function currentStatus($mutasi_benda_id)
    {

        $status = MutasiRepositoryStatusInterface::statusData;

        $result = '';

        $data = Mutasi_benda_model_eloquent::with(['benda_department.mutasi_benda_approval.employee', 'benda_department.department', 'benda_department.mutasi_benda_approval.log'])->where('mutasi_benda_id', $mutasi_benda_id)->first()->toArray();

        switch ($data['status']) {
            case '0':
                $result = $this->findCondition1($data);
                break;
            case '1':
                $result = 'All Approved';
                break;
            case '-1':
                $result = $status[$data['status']] . ' by ' . $this->findConditionRejected($data);
                break;
            case '2':
                $result = $status[$data['status']] . ' ' . $this->findCondition1($data);
                break;
            case '-2':
                $result = $status[$data['status']] . ' ' . $this->findCondition1($data);
                break;
            default:
                # code...
                break;
        }


        return $result;
    }

    private function getLastDept($mutasi_benda_id)
    {
        $MutasiRepository = new MutasiRepository();
        $data = $MutasiRepository->findByID($mutasi_benda_id)->toArray();
        $benda_department = $data['benda_department'];
        $lastIndex = count($benda_department) - 1;
        $department_id = $benda_department[$lastIndex]['department_id'];

        return $department_id;
    }
    public function updateStatusWhenIsDone($mutasi_benda_id)
    {
        Capsule::beginTransaction();
        try {
            $dataMutasiBenda = Mutasi_benda_model_eloquent::find($mutasi_benda_id);
            $dataMutasiBenda->status = '1';
            $dataMutasiBenda->save();

            $department_idlast = $this->getLastDept($mutasi_benda_id);

            $benda_id = $dataMutasiBenda->benda_id;
            $dataBenda = Benda_model_eloquent::find($benda_id);
            $dataBenda->department_id = $department_idlast;
            $dataBenda->save();

            Capsule::commit();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function afterReject($mutasi_benda_id)
    {
        Capsule::beginTransaction();
        try {
            $this->updateStatusFirst($mutasi_benda_id, '-1');
            // set all condition is '0'
            $statusMain = MutasiRepositoryStatusInterface::statusData;
            $statusLog = $statusMain['-1'];
            $MutasiRepository = new MutasiRepository();
            $data = $MutasiRepository->findByID($mutasi_benda_id)->toArray();
            $benda_department = $data['benda_department'];


            $findIndexDeptEmployeeApproved = $this->findIndexDeptEmployeeApproved($benda_department);

            if ($findIndexDeptEmployeeApproved !== null) {
                $dataDeptApproved = $benda_department[$findIndexDeptEmployeeApproved]['mutasi_benda_approval'];

                // update progress department
                $updDep = Mutasi_benda_department_approval_model_eloquent::find($benda_department[$findIndexDeptEmployeeApproved]['mutasi_benda_department_approval_id']);
                $updDep->status = '-1';
                $updDep->save();

                $findIndexEmpApproved = $this->findIndexEmpApproved($dataDeptApproved);
                if ($findIndexEmpApproved !== null) {
                    $dataEmpApproved = $dataDeptApproved[$findIndexEmpApproved];

                    $dataSavedLog = [
                        'mutasi_benda_approval_id' => $dataEmpApproved['mutasi_benda_approval_id'],
                        'desc' => $statusLog,
                        'status' => $dataEmpApproved['status'],
                        'created_by' =>  $this->CI->data['user']->id,
                    ];

                    $MutasiLogRepository = new MutasiLogRepository();
                    $MutasiLogRepository->insertLog($dataSavedLog);
                }
            }

            Capsule::commit();
            return true;
        } catch (\Throwable $th) {
            Capsule::rollback();
            return false;
        }

        return false;
    }

    public function statusBendaMutasi($benda_id)
    {
        $return = ['status' => null, 'message' => '', 'data' => []];
        $statusMutasiGrouping = [
            '1' => 'Done',
            '0' => 'Progress Mutasi',
            '2' => 'Progress Mutasi',
            '-1' => 'Cancel Mutasi',
            '2' => 'Progress Mutasi',
        ];

        $data = Mutasi_benda_model_eloquent::where('benda_id', $benda_id)->with(['jenis_mutasi', 'benda_department.mutasi_benda_approval.employee', 'benda_department.department', 'benda_department.mutasi_benda_approval.log'])->orderBy('mutasi_benda_id', 'desc')->first();

        if ($data) {
            $return['status'] = $statusMutasiGrouping[$data->status];
            $return['message'] = $this->currentStatus($data->mutasi_benda_id);
            $return['data'] = $data->toArray();
        }
        return $return;
    }
}
