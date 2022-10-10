<?php

namespace Modules\reimbursment\repository;

use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\administration\repository\JabatanRepository;
use Modules\reimbursment\models\Reimbursment_model_eloquent;
use Modules\administration\repository\approval\ApprovalRuleRepository;
use Modules\reimbursment\repository\main\ReimbursmentRepositoryInterface;
use Modules\reimbursment\models\Reimbursment_dept_approval_model_eloquent;
use Modules\reimbursment\models\Reimbursment_dept_approval_emp_model_eloquent;



class ReimbursmentRepository implements ReimbursmentRepositoryInterface
{
    protected $CI;
    public $statusMainApproval =   [
        '1' => 'Approved',
        '0' => 'Waiting',
        '-1' => 'Reject',
        '2' => 'Progress',
        '-2' => 'Awaiting',
        '99' => 'Terbayarkan'
    ];


    public function __construct()
    {
        $this->CI = &get_instance();
        $this->JabatanRepository = new JabatanRepository();
    }

    public function getCodeApproval()
    {
        $approval = $this->CI->db->where('name', 'Reimbursment')->get('approval_rule_config')->row();
        if ($approval) {
            return $approval->code_approval;
        }

        die('Approval Rule Config Not set');
    }

    public function opStatus()
    {
        return $this->statusMainApproval;
    }

    public function getApprovalData($codeApproval)
    {
    }
    public function getApprovalDataToInsert($codeApproval)
    {
        $approval_rule_id = $this->CI->db->where('code_approval', $codeApproval)->get('approval_rule_config')->row()->approval_rule_id;
        $ApprovalRuleRepository = new ApprovalRuleRepository();
        $approvalData = $ApprovalRuleRepository->findByID($approval_rule_id)->toArray();
        $type_approval = $approvalData['type_approval'];
        if (isset($approvalData['tagging_department']) && count($approvalData['tagging_department']) > 0) {
            $tagging_department = $approvalData['tagging_department'];
            $approvalAll = [];
            for ($i = 0; $i < count($tagging_department); $i++) {

                $status = '';
                if ($type_approval == '1') {
                    $status = ($i == 0) ? '0' : '-2';
                } else {
                    $status = '0';
                }

                $condition = '';
                if ($type_approval == '1') {
                    $condition = ($i == 0) ? '1' : '0';
                } else {
                    $condition = '1';
                }

                $temp = [
                    'reimbursment_dept_approval_id' => null,
                    'reimbursment_id' =>  '{getFrom_reimbursment_id}',
                    'department_id' => $tagging_department[$i]['department_id'],
                    'type_approval' => $tagging_department[$i]['pivot']['type_approval'],
                    'status' => $status,
                    'condition' => $condition,
                    'approval' => [],
                ];

                $approval = [];
                $jabatan = $tagging_department[$i]['pivot']['jabatan'];
                $type_approval_each_department =  $tagging_department[$i]['pivot']['type_approval'];
                for ($j = 0; $j < count($jabatan); $j++) {
                    $status = '';
                    if ($type_approval_each_department == '1') {
                        $status = ($i == 0 && $j == 0) ? '0' : '-2';
                    } else {
                        $status = '0';
                    }

                    $condition = '';
                    if ($type_approval_each_department == '1') {
                        $condition = ($i == 0 && $j == 0) ? '1' : '0';
                    } else {
                        $condition = '1';
                    }


                    $jabatan_id = $jabatan[$j]['jabatan_id'];

                    // find employee in departmen id and jabatan_id
                    $employee_data = $this->JabatanRepository->getEmployee($jabatan_id, $tagging_department[$i]['department_id']);
                    $employee_id = $employee_data['employee_id'];
                    $temp2 = [
                        'reimbursment_dept_approval_emp_id' => null,
                        'reimbursment_dept_approval_id' => '{getFrom_reimbursment_dept_approval_id}',
                        'employee_id' => $employee_id,
                        'status' => $status,
                        'condition' => $condition,
                    ];

                    $approval[] = $temp2;
                }

                $temp['approval'] = $approval;
                $approvalAll[] = $temp;
            }
            return ['status' => 'success', 'message' => '', 'data' => $approvalAll];
        } else {
            return ['status' => 'error', 'message' => 'Data approval belum di set'];
        }
    }
    public function loadApprovalMutasi($codeApproval)
    {
    }
    public function approve($item2)
    {
    }
    public function reject($item2)
    {
    }

    public function datatable($start, $length, $filter, $order, $tableParam)
    {
    }
    public function setOutputDatatable($get_data, $draw)
    {
    }

    public function getCode()
    {
        $prefixSettings = $this->CI->prefixSettings;
        $count = Reimbursment_model_eloquent::count();
        $year = Date('Y');
        $string = ($count == 0) ? $prefixSettings . '-' . $year . '-' . '000000' : Reimbursment_model_eloquent::orderByDesc('code')->limit(1)->first()->code; //the last entry from the database

        $split = explode('-', $string);
        if ($split[2] != $year) {
            $strNumber = '000000';
        } else {
            $strNumber = $split[0];
        }

        $strNumberNew = str_pad(intval($strNumber) + 1, 6, 0, STR_PAD_LEFT); //increment the number by 1 and pad with 0 in 
        $new = $prefixSettings . '-' . $year . '-' . $strNumberNew;
        return $new;
    }

    public function create($dataPost)
    {
        // get code
        $code = $this->getCode();
        $codeApproval = $this->CI->codeApproval;
        $dataSaved =  $this->getApprovalDataToInsert($codeApproval);
        if ($dataSaved['status'] == 'success') {
            $dataSaved = $dataSaved['data'];
            Capsule::beginTransaction();

            try {
                $Reimbursment = new Reimbursment_model_eloquent;
                $Reimbursment->code = $code;
                $Reimbursment->name = $dataPost['name'];
                $Reimbursment->date_reimbursment = $dataPost['date_reimbursment'];
                $Reimbursment->desc = $dataPost['desc'];
                $Reimbursment->value = $dataPost['value'];
                $Reimbursment->requested_by = $dataPost['requested_by'];
                $Reimbursment->created_by = $this->CI->data['user']->id;
                $Reimbursment->save();
                $reimbursment_id = $Reimbursment->reimbursment_id;

                for ($i = 0; $i < count($dataSaved); $i++) {
                    $dataSaved[$i]['reimbursment_id'] = $reimbursment_id;
                    $approval = $dataSaved[$i]['approval'];
                    $Reimbursment_dept_approval_model_eloquent = Reimbursment_dept_approval_model_eloquent::create($dataSaved[$i]);
                    $reimbursment_dept_approval_id = $Reimbursment_dept_approval_model_eloquent->reimbursment_dept_approval_id;
                    // 
                    for ($j = 0; $j < count($approval); $j++) {
                        $approval[$j]['reimbursment_dept_approval_id'] = $reimbursment_dept_approval_id;
                        $Reimbursment_dept_approval_emp_model_eloquent = Reimbursment_dept_approval_emp_model_eloquent::create($approval[$j]);
                        $reimbursment_dept_approval_emp_id = $Reimbursment_dept_approval_emp_model_eloquent->reimbursment_dept_approval_emp_id;
                        $dataSavedLog = [
                            'reimbursment_dept_approval_emp_id' => $reimbursment_dept_approval_emp_id,
                            'desc' => 'Approval Created',
                            'status' => $Reimbursment_dept_approval_emp_model_eloquent->status,
                            'created_by' =>  $this->CI->data['user']->id,
                        ];
                        $this->MutasiLogRepository->insertLog($dataSavedLog);
                    }
                }


                Capsule::commit();
                $return = array('message' => '', 'status' => 'success', 'code' => $code);
            } catch (\Throwable $th) {
                Capsule::rollback();
                $return = array('message' => $th->getMessage(), 'status' => 'error');
            }
        } else {
            $return = $dataSaved;
        }
        return $return;
    }
    public function delete($id)
    {
    }
    public function edit($id)
    {
    }
    public function findByID($id)
    {
        $reimbursment_id = $id;
        $data = Reimbursment_model_eloquent::with(['reimbursment_department.approval.employee', 'reimbursment_department.department', 'reimbursment_department.approval.log'])->where('reimbursment_id', $reimbursment_id)->first();
        if ($data) {
            return $data;
        }
        return [];
    }
}
