<?php

namespace Modules\reimbursment\repository;

use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\reimbursment\repository\ReimbursmentRepository;
use Modules\reimbursment\models\Reimbursment_model_eloquent;
use Modules\administration\models\Departments_model_eloquent;
use Modules\reimbursment\repository\ReimbursmentLogRepository;
use Modules\reimbursment\models\Reimbursment_dept_approval_model_eloquent;
use Modules\reimbursment\models\Reimbursment_dept_approval_emp_model_eloquent;
use Modules\reimbursment\repository\main\ReimbursmentRepositoryStatusInterface;

class ReimbursmentRepositoryStatus implements ReimbursmentRepositoryStatusInterface
{
    protected $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
    }
    public function updateStatusFirst($reimbursment_id, $status = '2')
    {
        $data = Reimbursment_model_eloquent::find($reimbursment_id);
        $data->status = $status;
        $data->save();
    }

    public function updateAfterApproval($reimbursment_id, $indexApproved, $indexApprovedDept)
    {
        Capsule::beginTransaction();
        try {
            $this->updateStatusFirst($reimbursment_id);

            $statusMain = ReimbursmentRepositoryStatusInterface::statusData;
            $statusLog = $statusMain['1'];
            $MutasiRepository = new ReimbursmentRepository();
            $data = $MutasiRepository->findByID($reimbursment_id)->toArray();
            $reimbursment_department = $data['reimbursment_department'];
            $AllEmpApproved = false;

            $findIndexDeptEmployeeApproved = $indexApprovedDept;


            if ($findIndexDeptEmployeeApproved !== null) {
                $dataDeptApproved = $reimbursment_department[$findIndexDeptEmployeeApproved]['approval'];

                // update progress department
                $updDep = Reimbursment_dept_approval_model_eloquent::find($reimbursment_department[$findIndexDeptEmployeeApproved]['reimbursment_dept_approval_id']);
                $updDep->status = '2';
                $updDep->save();

                $findIndexEmpApproved = $indexApproved;

                if ($findIndexEmpApproved !== null) {
                    $dataEmpApproved = $dataDeptApproved[$findIndexEmpApproved];
                    $dataSavedLog = [
                        'reimbursment_dept_approval_emp_id' => $dataEmpApproved['reimbursment_dept_approval_emp_id'],
                        'desc' => $statusLog,
                        'status' => $dataEmpApproved['status'],
                        'created_by' =>  $this->CI->data['user']->id,
                    ];

                    $MutasiLogRepository = new ReimbursmentLogRepository();
                    $MutasiLogRepository->insertLog($dataSavedLog);

                    $upd = Reimbursment_dept_approval_emp_model_eloquent::find($dataEmpApproved['reimbursment_dept_approval_emp_id']);
                    $upd->condition = '2';
                    $upd->save();
                }

                $nextApprovalEmp = $this->nextApprovalEmp($dataDeptApproved, $findIndexEmpApproved);


                // check on db if dept approval all is done for employee
                $mutDeptId = $dataEmpApproved['reimbursment_dept_approval_id'];
                $d = Reimbursment_dept_approval_emp_model_eloquent::where('status', '!=', '1')->where('reimbursment_dept_approval_id', $mutDeptId)->count();

                if ($d > 0 && $nextApprovalEmp) {
                    $dataEmpApprovedNext = $dataDeptApproved[$nextApprovalEmp];
                    $reimbursment_dept_approval_emp_id = $dataEmpApprovedNext['reimbursment_dept_approval_emp_id'];
                    $upd = Reimbursment_dept_approval_emp_model_eloquent::find($reimbursment_dept_approval_emp_id);
                    $upd->condition =  '1';
                    $upd->status =  '0';
                    $upd->save();
                } else {

                    if ($d == 0) {
                        $AllEmpApproved = true;
                        $reimbursment_dept_approval_id = $dataDeptApproved[0]['reimbursment_dept_approval_id'];
                        $dataDep = Reimbursment_dept_approval_model_eloquent::find($reimbursment_dept_approval_id);
                        $dataDep->status = '1';
                        $dataDep->condition = '2';
                        $dataDep->save();
                    }
                }
            }

            if ($AllEmpApproved) {
                $nextApproveDept = $this->nextApproveDept($reimbursment_department, $findIndexDeptEmployeeApproved);


                $dDept = Reimbursment_dept_approval_model_eloquent::where('status', '!=', '1')->where('reimbursment_id', $reimbursment_id)->count();

                if ($dDept > 0 && $nextApproveDept) {
                    $dataDeptNext = $reimbursment_department[$nextApproveDept];
                    $reimbursment_dept_approval_id = $dataDeptNext['reimbursment_dept_approval_id'];
                    $dataDep = Reimbursment_dept_approval_model_eloquent::find($reimbursment_dept_approval_id);
                    $dataDep->status = '0';
                    $dataDep->condition = '1';
                    $dataDep->save();

                    // set emp
                    $reimbursment_dept_approval_emp_id = $dataDeptNext['approval'][0]['reimbursment_dept_approval_emp_id'];
                    $upd = Reimbursment_dept_approval_emp_model_eloquent::find($reimbursment_dept_approval_emp_id);
                    $upd->condition =  '1';
                    $upd->status =  '0';
                    $upd->save();
                } else {
                    // all is done
                    if ($dDept == 0) {
                        $check =  $this->updateStatusWhenIsDone($reimbursment_id);
                        if (!$check) {
                            Capsule::rollback();
                            return false;
                        }
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
    public function currentStatus($reimbursment_id)
    {
        $status = ReimbursmentRepositoryStatusInterface::statusData;

        $result = '';

        $data = Reimbursment_model_eloquent::with(['reimbursment_department.approval.employee', 'reimbursment_department.department', 'reimbursment_department.approval.log'])->where('reimbursment_id', $reimbursment_id)->first()->toArray();

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
    public function updateStatusWhenIsDone($reimbursment_id)
    {
        Capsule::beginTransaction();
        try {
            $dataMutasiBenda = Reimbursment_model_eloquent::find($reimbursment_id);
            $dataMutasiBenda->status = '1';
            $dataMutasiBenda->save();


            Capsule::commit();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function afterReject($reimbursment_id, $indexApproved, $indexApprovedDept)
    {
        Capsule::beginTransaction();
        try {
            $this->updateStatusFirst($reimbursment_id, '-1');
            // set all condition is '0'
            $statusMain = ReimbursmentRepositoryStatusInterface::statusData;
            $statusLog = $statusMain['-1'];
            $MutasiRepository = new ReimbursmentRepository();
            $data = $MutasiRepository->findByID($reimbursment_id)->toArray();
            $reimbursment_department = $data['reimbursment_department'];

            $findIndexDeptEmployeeApproved = $indexApprovedDept;

            if ($findIndexDeptEmployeeApproved !== null) {
                $dataDeptApproved = $reimbursment_department[$findIndexDeptEmployeeApproved]['approval'];
                // update progress department
                $updDep = Reimbursment_dept_approval_model_eloquent::find($reimbursment_department[$findIndexDeptEmployeeApproved]['reimbursment_dept_approval_id']);
                $updDep->status = '-1';
                $updDep->save();

                $findIndexEmpApproved = $indexApproved;

                if ($findIndexEmpApproved !== null) {
                    $dataEmpApproved = $dataDeptApproved[$findIndexEmpApproved];

                    $dataSavedLog = [
                        'reimbursment_dept_approval_emp_id' => $dataEmpApproved['reimbursment_dept_approval_emp_id'],
                        'desc' => $statusLog,
                        'status' => $dataEmpApproved['status'],
                        'created_by' =>  $this->CI->data['user']->id,
                    ];

                    $MutasiLogRepository = new ReimbursmentLogRepository();
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
    public function findCondition1($data)
    {
        $status = ReimbursmentRepositoryStatusInterface::statusData;
        $condition = ReimbursmentRepositoryStatusInterface::condition;
        $rs = '';
        $reimbursment_department = $data['reimbursment_department'];

        for ($i = 0; $i < count($reimbursment_department); $i++) {
            $department_id = $reimbursment_department[$i]['department_id'];
            $condition = $reimbursment_department[$i]['condition'];
            if ($condition == '1') {
                $statusDepartment = $reimbursment_department[$i]['status'];
                $departmentData = Departments_model_eloquent::find($department_id);
                if ($statusDepartment == '0') {
                    $endWorld = ' On ' . $departmentData->name . ' Department';
                    $approval = $reimbursment_department[$i]['approval'];
                    for ($j = 0; $j < count($approval); $j++) {
                        $conditionEmp = $approval[$j]['condition'];
                        $statusEmp = $approval[$j]['status'];
                        if ($conditionEmp == '1') {

                            $rs .= '<li>' . $status[$statusEmp] . ' ' . $approval[$j]['employee']['nip'] . ' - ' . $approval[$j]['employee']['name'] . '</li>';
                        }
                    }

                    $rs .= '<br/>' . $endWorld;
                }
            }
        }

        return $rs;
    }
    public function findConditionRejected($data)
    {
        $rs = '';
        $rejectStatus = '-1';
        $reimbursment_department = $data['reimbursment_department'];

        for ($i = 0; $i < count($reimbursment_department); $i++) {
            $department_id = $reimbursment_department[$i]['department_id'];
            $statusDepartment = $reimbursment_department[$i]['status'];
            if ($statusDepartment == $rejectStatus) {
                $endWorld = ' On ' . $reimbursment_department[$i]['department']['name'] . ' Department';
                $approval = $reimbursment_department[$i]['approval'];
                for ($j = 0; $j < count($approval); $j++) {
                    $statusEmp = $approval[$j]['status'];
                    if ($statusEmp == $rejectStatus) {
                        $rs .= '<li>' . $approval[$j]['employee']['nip'] . ' - ' . $approval[$j]['employee']['name'] . '</li>';
                    }
                }
                $rs .= '<br/>' . $endWorld;
            }
        }

        return $rs;
    }

    private function nextApprovalEmp($dataDeptApproved, $findIndexEmpApproved)
    {
        $nextIndex = $findIndexEmpApproved + 1;
        if (isset($dataDeptApproved[$nextIndex])) {
            return $nextIndex;
        }

        return false;
    }

    private function nextApproveDept($reimbursment_department, $findIndexDeptEmployeeApproved)
    {
        $nextIndex = $findIndexDeptEmployeeApproved + 1;
        if (isset($reimbursment_department[$nextIndex])) {
            return $nextIndex;
        }


        return false;
    }
}
