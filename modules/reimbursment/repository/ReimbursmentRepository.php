<?php

namespace Modules\reimbursment\repository;

use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\administration\repository\JabatanRepository;
use Modules\reimbursment\models\Reimbursment_model_eloquent;
use Modules\reimbursment\repository\ReimbursmentLogRepository;
use Modules\reimbursment\repository\ReimbursmentRepositoryStatus;
use Modules\reimbursment\repository\ReimbursmentRuleActionRepository;
use Modules\administration\repository\approval\ApprovalRuleRepository;
use Modules\reimbursment\repository\main\ReimbursmentRepositoryInterface;
use Modules\reimbursment\models\Reimbursment_dept_approval_model_eloquent;
use Modules\reimbursment\models\Reimbursment_dept_approval_emp_model_eloquent;
use Modules\reimbursment\repository\main\ReimbursmentRepositoryStatusInterface;



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
        $this->ReimbursmentLogRepository = new ReimbursmentLogRepository();
        $this->ReimbursmentRepositoryStatus = new ReimbursmentRepositoryStatus();
        $this->ReimbursmentRuleAction = new ReimbursmentRuleActionRepository();
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
        $st = ['' => 'All'];
        return $st =  $st + $this->statusMainApproval;
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
    public function loadApprovalMutasi($reimbursment_id)
    {
        $newFormatData = [];
        $data = $this->findByID($reimbursment_id)->toArray();
        $statusMain = ReimbursmentRepositoryStatusInterface::statusData;
        $reimbursment_department = $data['reimbursment_department'];

        for ($i = 0; $i < count($reimbursment_department); $i++) {
            $newObj = [];

            $approvalNew = [];
            $approval = $reimbursment_department[$i]['approval'];
            for ($j = 0; $j < count($approval); $j++) {
                $obj = [];
                $obj['condition'] = $approval[$j]['condition'];

                if ($reimbursment_department[$i]['condition'] == '1') {
                    $obj['statusShow'] = $statusMain[$approval[$j]['status']];
                } else {
                    // change waiting to awaiting, replace status
                    if ($approval[$j]['status'] == '0') {
                        $obj['statusShow'] = $statusMain['-2'];
                    } else {
                        $obj['statusShow'] = $statusMain[$approval[$j]['status']];
                    }
                }


                $obj['employeeData'] = $approval[$j]['employee'];
                $obj['employee_id'] =  $approval[$j]['employee_id'];
                $obj['reimbursment_dept_approval_emp_id'] = $approval[$j]['reimbursment_dept_approval_emp_id'];
                $obj['reimbursment_dept_approval_id'] = $approval[$j]['reimbursment_dept_approval_id'];
                $obj['status'] = $approval[$j]['status'];
                $obj['log'] = $approval[$j]['log'];
                $approvalNew[] = $obj;
            }
            $condition = $reimbursment_department[$i]['condition'];
            $departmentData = $reimbursment_department[$i]['department'];
            $department_id = $reimbursment_department[$i]['department_id'];
            $reimbursment_dept_approval_id = $reimbursment_department[$i]['reimbursment_dept_approval_id'];
            $status =  $reimbursment_department[$i]['status'];
            $statusShow =  $statusMain[$reimbursment_department[$i]['status']];
            $type_approval =  $reimbursment_department[$i]['type_approval'];

            $newObj['approval'] = $approvalNew;
            $newObj['condition'] = $condition;
            $newObj['departmentData'] = $departmentData;
            $newObj['department_id'] = $department_id;
            $newObj['reimbursment_dept_approval_id'] = $reimbursment_dept_approval_id;
            $newObj['reimbursment_id'] = $reimbursment_id;
            $newObj['status'] = $status;
            $newObj['statusShow'] = $statusShow;
            $newObj['type_approval'] = $type_approval;


            $newFormatData[] = $newObj;
        }

        return ['status' => 'success', 'message' => '', 'data' => $newFormatData];
    }
    public function approve($item2)
    {
        $dataMutasiDep = Reimbursment_dept_approval_model_eloquent::find($item2['reimbursment_dept_approval_id']);
        $check = $this->ReimbursmentRuleAction->approve($dataMutasiDep->reimbursment_id, $dataMutasiDep->department_id, $item2['employee_id']);
        if ($check['status'] == 'success') {
            // do approval
            Capsule::beginTransaction();
            try {
                $reimbursment_dept_approval_emp_id =  $item2['reimbursment_dept_approval_emp_id'];
                $Reimbursment_dept_approval_emp_model_eloquent = Reimbursment_dept_approval_emp_model_eloquent::find($reimbursment_dept_approval_emp_id);

                $Reimbursment_dept_approval_emp_model_eloquent->status =  '1';
                $Reimbursment_dept_approval_emp_model_eloquent->save();

                $reimbursment_dept_approval_id = $Reimbursment_dept_approval_emp_model_eloquent->reimbursment_dept_approval_id;

                // find index
                $indexApproved = $this->getIndexBydata($reimbursment_dept_approval_id, $reimbursment_dept_approval_emp_id);

                // find index dept
                $indexApprovedDept = $this->getIndexDeptBydata($reimbursment_dept_approval_id, $dataMutasiDep->mutasi_benda_id);

                $updateAfterApproval =  $this->ReimbursmentRepositoryStatus->updateAfterApproval($dataMutasiDep->reimbursment_id, $indexApproved, $indexApprovedDept);

                if ($updateAfterApproval || $updateAfterApproval == 1) {
                    Capsule::commit();
                    $return = array('message' => '', 'status' => 'success');
                } else {
                    Capsule::rollback();
                    $return = array('message' => 'Something wrong about database', 'status' => 'error');
                }
            } catch (\Throwable $th) {
                Capsule::rollback();
                $return = array('message' => $th->getMessage(), 'status' => 'error');
            }
        } else {
            $return = $check;
        }
        return $return;
    }
    public function reject($item2)
    {
        $dataMutasiDep = Reimbursment_dept_approval_model_eloquent::find($item2['reimbursment_dept_approval_id']);
        $check = $this->ReimbursmentRuleAction->approve($dataMutasiDep->reimbursment_id, $dataMutasiDep->department_id, $item2['employee_id']);
        if ($check['status'] == 'success') {
            Capsule::beginTransaction();
            try {
                $reimbursment_dept_approval_emp_id =  $item2['reimbursment_dept_approval_emp_id'];
                $Reimbursment_dept_approval_emp_model_eloquent = Reimbursment_dept_approval_emp_model_eloquent::find($reimbursment_dept_approval_emp_id);
                $Reimbursment_dept_approval_emp_model_eloquent->status =  '-1';
                $Reimbursment_dept_approval_emp_model_eloquent->save();

                $reimbursment_dept_approval_id = $Reimbursment_dept_approval_emp_model_eloquent->reimbursment_dept_approval_id;

                // find index
                $indexApproved = $this->getIndexBydata($reimbursment_dept_approval_id, $reimbursment_dept_approval_emp_id);

                // find index dept
                $indexApprovedDept = $this->getIndexDeptBydata($reimbursment_dept_approval_id, $dataMutasiDep->reimbursment_id);


                $afterReject =  $this->ReimbursmentRepositoryStatus->afterReject($dataMutasiDep->reimbursment_id, $indexApproved, $indexApprovedDept);
                if ($afterReject || $afterReject == 1) {
                    Capsule::commit();
                    $return = array('message' => '', 'status' => 'success');
                } else {
                    Capsule::rollback();
                    $return = array('message' => 'Something wrong about database', 'status' => 'error');
                }
            } catch (\Throwable $th) {
                Capsule::rollback();
                $return = array('message' => $th->getMessage(), 'status' => 'error');
            }
        } else {
            $return = $check;
        }
        return $return;
    }

    public function getIndexDeptBydata($reimbursment_dept_approval_id, $reimbursment_id)
    {

        $Reimbursment_dept_approval_model_eloquent = Reimbursment_dept_approval_model_eloquent::where('reimbursment_id', $reimbursment_id)->get()->toArray();

        for ($i = 0; $i < count($Reimbursment_dept_approval_model_eloquent); $i++) {
            if ($reimbursment_dept_approval_id == $Reimbursment_dept_approval_model_eloquent[$i]['reimbursment_dept_approval_id']) {
                return $i;
                break;
            }
        }

        return false;
    }


    public function getIndexBydata($reimbursment_dept_approval_id, $reimbursment_dept_approval_emp_id)
    {
        $Reimbursment_dept_approval_emp_model_eloquent = Reimbursment_dept_approval_emp_model_eloquent::where('reimbursment_dept_approval_id', $reimbursment_dept_approval_id)->get()->toArray();

        for ($i = 0; $i < count($Reimbursment_dept_approval_emp_model_eloquent); $i++) {
            if ($reimbursment_dept_approval_emp_id == $Reimbursment_dept_approval_emp_model_eloquent[$i]['reimbursment_dept_approval_emp_id']) {
                return $i;
                break;
            }
        }

        return false;
    }

    public function datatable($start, $length, $filter, $order, $tableParam)
    {
        $columns = isset($tableParam) ? $tableParam['columns'] : [];
        $datas = new Reimbursment_model_eloquent;
        $datas = $datas->join('employees', 'employees.employee_id', '=', 'reimbursment.requested_by')
            ->select(
                'reimbursment.reimbursment_id',
                'reimbursment.code',
                'reimbursment.value',
                'reimbursment.name',
                'reimbursment.status',
                'reimbursment.date_reimbursment',
                'reimbursment.desc',
                'employees.name as nameEmployees',
                'reimbursment.created_at',
                'reimbursment.created_by',
                'reimbursment.updated_at',
                'reimbursment.updated_by',
                Capsule::raw(
                    '(SELECT GROUP_CONCAT(DISTINCT departments.`name` ORDER BY departments.department_id ASC SEPARATOR ", ") FROM departments
                join reimbursment_dept_approval as mba on mba.department_id = departments.department_id
               where     mba.reimbursment_id = reimbursment.reimbursment_id) as tag_department'
                )
            )
            ->groupby('reimbursment.reimbursment_id');

        if ($filter) {
            // filter
            $datas = $datas->where(function ($query) use ($filter, $columns) {
                foreach ($filter as $column => $value) {
                    if (!empty($value)) {
                        $query->where($columns[$column]['name'], 'like', '' . $value . '%');
                    }
                }
            });
        }

        $recordsFiltered = $datas;
        $countFiltered = $recordsFiltered->count();
        // order
        if ($order) {
            $order['column'] = $columns[$order['column']]['name'];
            $datas = $datas->orderByRaw($order['column'] . ' ' . $order['dir']);
        }

        $datas = $datas->offset($start)
            ->limit($length);

        $datas = $datas->get();
        return ['dataRaw' => $datas, 'recordsTotal' => Reimbursment_model_eloquent::count(), 'recordsFiltered' => $countFiltered];
    }
    public function setOutputDatatable($get_data, $draw)
    {
        $output = $get_data;
        $output['data'] = array();
        if (count($get_data['dataRaw']) > 0) {
            $dataRaw = $get_data['dataRaw']->toArray();
            for ($i = 0; $i < count($dataRaw); $i++) {
                $currentStatus = $this->ReimbursmentRepositoryStatus->currentStatus($dataRaw[$i]['reimbursment_id']);
                $payload = array(
                    'id' => $dataRaw[$i]['reimbursment_id']
                );
                $encry = get_jwt_encryption($payload);
                $output['data'][] = array(
                    '<span style="color:green">' . $dataRaw[$i]['code'] . '</span><br/>' . $dataRaw[$i]['name'],
                    $dataRaw[$i]['nameEmployees'],
                    money($dataRaw[$i]['value']),
                    get_date($dataRaw[$i]['date_reimbursment']),
                    $this->statusMainApproval[$dataRaw[$i]['status']],
                    nl2br($dataRaw[$i]['desc']),
                    get_date_time($dataRaw[$i]['created_at']) . ' <br/>' . $this->CI->m_master->get_username_by($dataRaw[$i]['created_by']),
                    '<div class = "action-buttons">' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/view') ? '<a class="' . lang('button_view_class') . '" title="' . lang('view') . '" href="' . $this->CI->data['module_url'] . 'view/' . $encry . '">' . lang('button_view') . '</a>' : '') .
                        '&nbsp  ' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/delete') ? '<a tabindex="-1" title="' . lang('delete') . '" class="' . lang('button_delete_class') . ' delete_row_default" href="' . $this->CI->data['module_url'] . 'delete/' . $encry . '">' . lang('button_delete') . '</a>' : '') .
                        '</div>'
                );
            }
        }
        $output['draw'] = $draw++;

        return $output;
    }

    public function getCode()
    {
        $prefixSettings = $this->CI->prefixSettings;
        $count = Reimbursment_model_eloquent::count();;
        $year = Date('Y');
        $string = ($count == 0) ? $prefixSettings . '-' . $year . '-' . '000000' : Reimbursment_model_eloquent::orderByDesc('code')->limit(1)->first()->code; //the last entry from the database

        $split = explode('-', $string);
        if ($split[1] != $year) {
            $strNumber = '000000';
        } else {
            $strNumber = $split[2];
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

                $approval_rule_id = $this->CI->db->where('code_approval', $codeApproval)->get('approval_rule_config')->row()->approval_rule_id;
                $ApprovalRuleRepository = new ApprovalRuleRepository();
                $approvalData = $ApprovalRuleRepository->findByID($approval_rule_id)->toArray();

                $Reimbursment = new Reimbursment_model_eloquent;
                $Reimbursment->code = $code;
                $Reimbursment->name = $dataPost['name'];
                $Reimbursment->date_reimbursment = $dataPost['date_reimbursment'];
                $Reimbursment->type_approval = $approvalData['type_approval'];
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
                        $this->ReimbursmentLogRepository->insertLog($dataSavedLog);
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
        $reimbursment_id = $id;
        $check =  $this->ReimbursmentRuleAction->delete($reimbursment_id);
        if ($check['status'] == 'success') {
            Capsule::beginTransaction();
            try {
                $data = Reimbursment_model_eloquent::find($reimbursment_id);

                $data->delete();

                Capsule::commit();
                $return = array('message' => '', 'status' => 'success');
            } catch (\Throwable $th) {
                Capsule::rollback();
                $return = array('message' => $th->getMessage(), 'status' => 'error');
            }
        } else {
            $return = $check;
        }


        return $return;
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

    public function paid($dataPost)
    {
        try {
            $data = Reimbursment_model_eloquent::find($dataPost['reimbursment_id']);
            $data->status = '99';
            $data->updated_by = $this->CI->data['user']->id;
            $data->save();

            $this->sendEmail($data);
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }

    private function sendEmail($dataPost)
    {
    }
}
