<?php

namespace Repository\mutasi;

use Repository\mutasi\MutasiRuleAction;
use Repository\mutasi\MutasiLogRepository;
use Repository\mutasi\JenisMutasiRepository;
use Repository\mutasi\MutasiRepositoryStatus;
use Modules\benda\models\Benda_model_eloquent;
use Repository\administration\JabatanRepository;
use Illuminate\Database\Capsule\Manager as Capsule;
use Repository\mutasi\main\MutasiRepositoryInterface;
use Modules\mutasi\models\Mutasi_benda_model_eloquent;
use Modules\administration\models\Jabatan_model_eloquent;
use Repository\mutasi\main\MutasiRepositoryStatusInterface;
use Modules\administration\models\Departments_model_eloquent;
use Modules\mutasi\models\Mutasi_benda_approval_model_eloquent;
use Modules\mutasi\models\Mutasi_benda_department_approval_model_eloquent;


class MutasiRepository implements MutasiRepositoryInterface
{
    protected $CI;
    protected $JabatanRepository;
    protected $MutasiLogRepository;
    protected $MutasiRuleAction;
    protected $statusMainMutasi =   [
        '1' => 'Approved',
        '0' => 'Waiting',
        '-1' => 'Reject',
        '2' => 'Progress',
        '-2' => 'Awaiting',
    ];
    protected $MutasiRepositoryStatus;


    public function __construct()
    {
        $this->CI = &get_instance();
        $this->JabatanRepository = new JabatanRepository();
        $this->MutasiLogRepository = new MutasiLogRepository();
        $this->MutasiRepositoryStatus = new MutasiRepositoryStatus();
        $this->MutasiRuleAction = new MutasiRuleAction();
    }

    public function getApprovalData($jenis_mutasi_id)
    {
        $JenisMutasiRepository = new JenisMutasiRepository();
        $JenisMutasiData = $JenisMutasiRepository->findByID($jenis_mutasi_id)->toArray();

        $type_approval = $JenisMutasiData['type_approval'];

        if (isset($JenisMutasiData['tagging_department']) && count($JenisMutasiData['tagging_department']) > 0) {
            $tagging_department = $JenisMutasiData['tagging_department'];
            $mutasi_benda_approval = [];
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

                $departmentData = Departments_model_eloquent::find($tagging_department[$i]['department_id'])->toArray();
                $temp = [
                    'mutasi_benda_department_approval_id' => null,
                    'mutasi_benda_id' => '{getFromMutasiBenda}',
                    'department_id' => $tagging_department[$i]['department_id'],
                    'type_approval' => $tagging_department[$i]['pivot']['type_approval'],
                    'status' => $status,
                    'condition' => $condition,
                    'approval' => [],
                    'departmentData' => $departmentData,
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
                    $jabatanData = Jabatan_model_eloquent::find($jabatan_id)->toArray();

                    // find employee in departmen id and jabatan_id
                    $employee_data = $this->JabatanRepository->getEmployee($jabatan_id, $tagging_department[$i]['department_id']);
                    if (!$employee_data) {
                        return ['status' => 'error', 'message' => 'Data approval belum di set on ' . $tagging_department[$i]['name'] . ' - Jabatan ' . $jabatanData['name']];
                        break;
                    }
                    $employee_id = $employee_data['employee_id'];
                    $temp2 = [
                        'mutasi_benda_approval_id' => null,
                        'mutasi_benda_department_approval_id' => '{getFromMutasiBendaApproval}',
                        'employee_id' => $employee_id,
                        'status' => $status,
                        'condition' => $condition,
                        'jabatanData' => $jabatanData,
                        'employeeData' => $employee_data,
                    ];

                    $approval[] = $temp2;
                }

                $temp['approval'] = $approval;
                $mutasi_benda_approval[] = $temp;
            }

            return ['status' => 'success', 'message' => '', 'data' => $mutasi_benda_approval];
        } else {
            return ['status' => 'error', 'message' => 'Data approval belum di set'];
        }
    }

    public function getApprovalDataToInsert($jenis_mutasi_id)
    {
        $JenisMutasiRepository = new JenisMutasiRepository();
        $JenisMutasiData = $JenisMutasiRepository->findByID($jenis_mutasi_id)->toArray();
        $type_approval = $JenisMutasiData['type_approval'];
        if (isset($JenisMutasiData['tagging_department']) && count($JenisMutasiData['tagging_department']) > 0) {
            $tagging_department = $JenisMutasiData['tagging_department'];
            $mutasi_benda_approval = [];
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
                    'mutasi_benda_department_approval_id' => null,
                    'mutasi_benda_id' => '{getFromMutasiBenda}',
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
                        'mutasi_benda_approval_id' => null,
                        'mutasi_benda_department_approval_id' => '{getFromMutasiBendaApproval}',
                        'employee_id' => $employee_id,
                        'status' => $status,
                        'condition' => $condition,
                    ];

                    $approval[] = $temp2;
                }

                $temp['approval'] = $approval;
                $mutasi_benda_approval[] = $temp;
            }

            return ['status' => 'success', 'message' => '', 'data' => $mutasi_benda_approval];
        } else {
            return ['status' => 'error', 'message' => 'Data approval belum di set'];
        }
    }

    public function datatable($start, $length, $filter, $order, $tableParam)
    {
        $columns = isset($tableParam) ? $tableParam['columns'] : [];
        $datas = new Mutasi_benda_model_eloquent;
        $datas = $datas->join('bendas', 'bendas.benda_id', '=', 'mutasi_benda.benda_id')
            ->join('employees', 'employees.employee_id', '=', 'mutasi_benda.requested_by')
            ->join('jenis_mutasi', 'jenis_mutasi.jenis_mutasi_id', '=', 'mutasi_benda.jenis_mutasi_id')
            ->select(
                'mutasi_benda.mutasi_benda_id',
                'bendas.name',
                'mutasi_benda.status',
                'employees.name as nameEmployees',
                'mutasi_benda.created_at',
                'jenis_mutasi.name as jmName',
                Capsule::raw(
                    '(SELECT GROUP_CONCAT(DISTINCT departments.`name` ORDER BY departments.department_id ASC SEPARATOR ", ") FROM departments
                join mutasi_benda_department_approval as mba on mba.department_id = departments.department_id
               where     mba.mutasi_benda_id = mutasi_benda.mutasi_benda_id) as tag_department'
                )
            )
            ->where('bendas.active', 1)
            ->whereRaw('jenis_mutasi.deleted_at is NULL')
            ->groupby('mutasi_benda.mutasi_benda_id');

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
        return ['dataRaw' => $datas, 'recordsTotal' => Mutasi_benda_model_eloquent::count(), 'recordsFiltered' => $countFiltered];
    }

    public function setOutputDatatable($get_data, $draw)
    {
        $output = $get_data;
        $output['data'] = array();
        if (count($get_data['dataRaw']) > 0) {
            $dataRaw = $get_data['dataRaw']->toArray();
            for ($i = 0; $i < count($dataRaw); $i++) {
                $currentStatus = $this->MutasiRepositoryStatus->currentStatus($dataRaw[$i]['mutasi_benda_id']);
                $output['data'][] = array(
                    $dataRaw[$i]['mutasi_benda_id'],
                    $dataRaw[$i]['name'],
                    $dataRaw[$i]['jmName'],
                    $dataRaw[$i]['tag_department'],
                    $this->statusMainMutasi[$dataRaw[$i]['status']],
                    $currentStatus,
                    $dataRaw[$i]['nameEmployees'],
                    get_date_time($dataRaw[$i]['created_at']),
                    '<div class = "hidden-sm hidden-xs action-buttons">' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/view') ? '<a class="' . lang('button_view_class') . '" title="' . lang('view') . '" href="' . $this->CI->data['module_url'] . 'view/' . $dataRaw[$i]['mutasi_benda_id'] . '">' . lang('button_view') . '</a>' : '') .
                        '&nbsp  ' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/delete') ? '<a tabindex="-1" title="' . lang('delete') . '" class="' . lang('button_delete_class') . ' delete_row_default" href="' . $this->CI->data['module_url'] . 'delete/' . $dataRaw[$i]['mutasi_benda_id'] . '">' . lang('button_delete') . '</a>' : '') .
                        '</div>'
                );
            }
        }
        $output['draw'] = $draw++;

        return $output;
    }
    public function validationManually($dataPost)
    {
        $this->CI->load->library('form_validation');
        $this->CI->form_validation->set_data($dataPost);

        $form_validation_arr = array(
            array(
                'field' => 'benda_id',
                'label' => 'Benda',
                'rules' => 'required',
            ),
            array(
                'field' => 'requested_by',
                'label' => 'Requester',
                'rules' => 'required',
            ),
            array(
                'field' => 'jenis_mutasi_id',
                'label' => 'Jenis Mutasi',
                'rules' => 'required',
            ),
        );

        $this->CI->form_validation->set_rules($form_validation_arr);
        if ($this->CI->form_validation->run() === true) {
            $return = array('message' => '', 'status' => 'success');
        } else {
            $return = array('message' => validation_errors(), 'status' => 'error');
        }

        return $return;
    }

    public function validationManuallyMultiBenda($dataPost)
    {
        $this->CI->load->library('form_validation');
        $this->CI->form_validation->set_data($dataPost);

        if (count($dataPost) == 0) {
            $return = array('message' => 'Benda is required', 'status' => 'error');
            return $return;
        }

        $form_validation_arr = array(
            array(
                'field' => 'requested_by',
                'label' => 'Requester',
                'rules' => 'required',
            ),
            array(
                'field' => 'jenis_mutasi_id',
                'label' => 'Jenis Mutasi',
                'rules' => 'required',
            ),
            array(
                'field' => 'reason',
                'label' => 'Reason',
                'rules' => 'required',
            ),
        );

        $this->CI->form_validation->set_rules($form_validation_arr);
        if ($this->CI->form_validation->run() === true) {
            $benda_id = $dataPost['benda_id'];
            $jenis_mutasi_id = $dataPost['jenis_mutasi_id'];
            $boolValid = true;
            for ($i = 0; $i < count($benda_id); $i++) {
                $check =  $this->MutasiRuleAction->createMutasi($benda_id[$i], $jenis_mutasi_id);
                if ($check['status'] != 'success') {
                    $boolValid = false;
                    $return = $check;
                    break;
                }
            }

            if ($boolValid) {
                $return = array('message' => '', 'status' => 'success');
            }
        } else {
            $return = array('message' => validation_errors(), 'status' => 'error');
        }

        return $return;
    }

    public function createManyBenda($dataPost)
    {
        $benda_id = $dataPost['benda_id'];
        $newDataPost = $dataPost;
        Capsule::beginTransaction();
        try {
            for ($i = 0; $i < count($benda_id); $i++) {
                $newDataPost['benda_id'] = $benda_id[$i];
                $createMany = $this->create($newDataPost);
                if ($createMany['status'] != 'success') {
                    $return = $createMany;
                    return $return;
                }
            }
            Capsule::commit();
            $return = array('message' => '', 'status' => 'success');
        } catch (\Throwable $th) {
            Capsule::rollback();
            $return = array('message' => $th->getMessage(), 'status' => 'error');
        }

        return $return;
    }


    public function create($dataPost)
    {
        /*
        $dataPost = [
            'benda_id' => 1,
            'requested_by' => 1,
        ];
        */

        /*
        $dataSaved = [
            'mutasi_benda' => [ // manually from save
                'mutasi_benda_id' => '{increment}',
                'benda_id' => '',
                'requested_by' => '{select employee}',
                'status' => '0',
                'created_by' => $this->CI->data['user']->id,
            ],
            'mutasi_benda_approval' => [
                [
                    'mutasi_benda_approval_id' => '{increment}',
                    'mutasi_benda_id' => '{getFromMutasiBenda}',
                    'department_id' => '',
                    'type_approval' => '',
                    'status' => '0',
                    'condition' => '1',
                    'approval' => [
                        [
                            'mutasi_benda_approval_id' => '{increment}',
                            'mutasi_benda_department_approval_id' => '{getFromMutasiBendaApproval}',
                            'employee_id' => '',
                            'status' => '0',
                            'condition' => '1',
                        ],
                        [
                            'mutasi_benda_approval_id' => '{increment}',
                            'mutasi_benda_department_approval_id' => '{getFromMutasiBendaApproval}',
                            'employee_id' => '',
                            'status' => '-2',
                            'condition' => '0',
                        ],

                    ],
                ],
                [
                    'mutasi_benda_approval_id' => '{increment}',
                    'mutasi_benda_id' => '{getFromMutasiBenda}',
                    'department_id' => '',
                    'type_approval' => '',
                    'status' => '-2',
                    'condition' => '0',
                    'approval' => [
                        [
                            'mutasi_benda_approval_id' => '{increment}',
                            'mutasi_benda_department_approval_id' => '{getFromMutasiBendaApproval}',
                            'employee_id' => '',
                            'status' => '-2',
                            'condition' => '0',
                        ],

                    ],
                ],
                [
                    'mutasi_benda_approval_id' => '{increment}',
                    'mutasi_benda_id' => '{getFromMutasiBenda}',
                    'department_id' => '',
                    'type_approval' => '',
                    'status' => '-2',
                    'condition' => '0',
                    'approval' => [
                        [
                            'mutasi_benda_approval_id' => '{increment}',
                            'mutasi_benda_department_approval_id' => '{getFromMutasiBendaApproval}',
                            'employee_id' => '',
                            'status' => '-2',
                            'condition' => '0',
                        ],

                    ],
                ],
            ],


        ];
        */

        $benda_id =  $dataPost['benda_id'];
        $jenis_mutasi_id =  $dataPost['jenis_mutasi_id'];
        $reason =  $dataPost['reason'];
        $dataSaved =  $this->getApprovalDataToInsert($jenis_mutasi_id);
        if ($dataSaved['status'] == 'success') {
            $dataSaved = $dataSaved['data'];
            Capsule::beginTransaction();
            try {

                $Mutasi_benda = new Mutasi_benda_model_eloquent;
                $Mutasi_benda->benda_id = $benda_id;
                $Mutasi_benda->jenis_mutasi_id = $jenis_mutasi_id;
                $Mutasi_benda->reason = $reason;
                $Mutasi_benda->requested_by = $dataPost['requested_by'];
                $Mutasi_benda->created_by = $this->CI->data['user']->id;
                $Mutasi_benda->save();
                $mutasi_benda_id = $Mutasi_benda->mutasi_benda_id;

                for ($i = 0; $i < count($dataSaved); $i++) {
                    $dataSaved[$i]['mutasi_benda_id'] = $mutasi_benda_id;
                    $approval = $dataSaved[$i]['approval'];
                    $Mutasi_benda_department_approval = Mutasi_benda_department_approval_model_eloquent::create($dataSaved[$i]);
                    //$Mutasi_benda_department_approval->mutasi_benda_approval()->createMany($approval);
                    $mutasi_benda_department_approval_id = $Mutasi_benda_department_approval->mutasi_benda_department_approval_id;
                    for ($j = 0; $j < count($approval); $j++) {
                        $approval[$j]['mutasi_benda_department_approval_id'] = $mutasi_benda_department_approval_id;
                        $Mutasi_benda_approval = Mutasi_benda_approval_model_eloquent::create($approval[$j]);
                        $mutasi_benda_approval_id = $Mutasi_benda_approval->mutasi_benda_approval_id;
                        $dataSavedLog = [
                            'mutasi_benda_approval_id' => $mutasi_benda_approval_id,
                            'desc' => 'Approval Created',
                            'status' => $Mutasi_benda_approval->status,
                            'created_by' =>  $this->CI->data['user']->id,
                        ];
                        $this->MutasiLogRepository->insertLog($dataSavedLog);
                    }
                }

                Capsule::commit();
                $return = array('message' => '', 'status' => 'success');
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
        $mutasi_benda_id = $id;
        $check =  $this->MutasiRuleAction->deleteMutasi($mutasi_benda_id);
        if ($check['status'] == 'success') {
            Capsule::beginTransaction();
            try {
                // $data = $this->findByID($mutasi_benda_id)->toArray();
                // $benda_department = $data['benda_department'];
                // for ($i = 0; $i < count($benda_department); $i++) {
                //     $mutasi_benda_approval = $benda_department[$i]['mutasi_benda_approval'];
                //     for ($j = 0; $j < count($mutasi_benda_approval); $j++) {
                //         $mutasi_benda_approval_id = $mutasi_benda_approval[$j]['mutasi_benda_approval_id'];
                //         $Mutasi_benda_approval = Mutasi_benda_approval_model_eloquent::find($mutasi_benda_approval_id);
                //         $Mutasi_benda_approval->log()->delete();
                //         $Mutasi_benda_approval->delete();
                //     }
                // }

                $mutasiBenda = Mutasi_benda_model_eloquent::find($mutasi_benda_id);
                //$mutasiBenda->benda_department()->delete();
                $mutasiBenda->delete();

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
    public function movePositionBenda($benda_id)
    {
    }
    public function edit($id)
    {
    }

    public function findByID($id)
    {
        $mutasi_benda_id = $id;
        $data = Mutasi_benda_model_eloquent::with(['benda_department.mutasi_benda_approval.employee', 'benda_department.department', 'benda_department.mutasi_benda_approval.log'])->where('mutasi_benda_id', $mutasi_benda_id)->first();

        return $data;
    }

    public function loadApprovalMutasi($mutasi_benda_id)
    {
        $newFormatData = [];
        $data = $this->findByID($mutasi_benda_id)->toArray();
        $statusMain = MutasiRepositoryStatusInterface::statusData;
        $benda_department = $data['benda_department'];
        for ($i = 0; $i < count($benda_department); $i++) {
            $newObj = [];

            $approval = [];
            $mutasi_benda_approval = $benda_department[$i]['mutasi_benda_approval'];
            for ($j = 0; $j < count($mutasi_benda_approval); $j++) {
                $obj = [];
                $obj['condition'] = $mutasi_benda_approval[$j]['condition'];
                $obj['statusShow'] = $statusMain[$mutasi_benda_approval[$j]['status']];
                $obj['employeeData'] = $mutasi_benda_approval[$j]['employee'];
                $obj['employee_id'] =  $mutasi_benda_approval[$j]['employee_id'];
                $obj['mutasi_benda_approval_id'] = $mutasi_benda_approval[$j]['mutasi_benda_approval_id'];
                $obj['mutasi_benda_department_approval_id'] = $mutasi_benda_approval[$j]['mutasi_benda_department_approval_id'];
                $obj['status'] = $mutasi_benda_approval[$j]['status'];
                $obj['log'] = $mutasi_benda_approval[$j]['log'];
                $approval[] = $obj;
            }
            $condition = $benda_department[$i]['condition'];
            $departmentData = $benda_department[$i]['department'];
            $department_id = $benda_department[$i]['department_id'];
            $mutasi_benda_department_approval_id = $benda_department[$i]['mutasi_benda_department_approval_id'];
            $mutasi_benda_id =  $benda_department[$i]['mutasi_benda_id'];
            $status =  $benda_department[$i]['status'];
            $statusShow =  $statusMain[$benda_department[$i]['status']];
            $type_approval =  $benda_department[$i]['type_approval'];

            $newObj['approval'] = $approval;
            $newObj['condition'] = $condition;
            $newObj['departmentData'] = $departmentData;
            $newObj['department_id'] = $department_id;
            $newObj['mutasi_benda_department_approval_id'] = $mutasi_benda_department_approval_id;
            $newObj['mutasi_benda_id'] = $mutasi_benda_id;
            $newObj['status'] = $status;
            $newObj['statusShow'] = $statusShow;
            $newObj['type_approval'] = $type_approval;


            $newFormatData[] = $newObj;
        }

        return ['status' => 'success', 'message' => '', 'data' => $newFormatData];
    }

    public function approve($item2)
    {
        $dataMutasiDep = Mutasi_benda_department_approval_model_eloquent::find($item2['mutasi_benda_department_approval_id']);
        $check = $this->MutasiRuleAction->approveMutasi($dataMutasiDep->mutasi_benda_id, $dataMutasiDep->department_id, $item2['employee_id']);
        if ($check['status'] == 'success') {
            // do approval
            Capsule::beginTransaction();
            try {
                $mutasi_benda_approval_id =  $item2['mutasi_benda_approval_id'];
                $Mutasi_benda_approval_model_eloquent = Mutasi_benda_approval_model_eloquent::find($mutasi_benda_approval_id);
                $Mutasi_benda_approval_model_eloquent->status =  '1';
                $Mutasi_benda_approval_model_eloquent->save();
                $updateAfterApproval =  $this->MutasiRepositoryStatus->updateAfterApproval($dataMutasiDep->mutasi_benda_id);
                if ($updateAfterApproval) {
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
        $dataMutasiDep = Mutasi_benda_department_approval_model_eloquent::find($item2['mutasi_benda_department_approval_id']);
        $check = $this->MutasiRuleAction->approveMutasi($dataMutasiDep->mutasi_benda_id, $dataMutasiDep->department_id, $item2['employee_id']);
        if ($check['status'] == 'success') {
            Capsule::beginTransaction();
            try {
                $mutasi_benda_approval_id =  $item2['mutasi_benda_approval_id'];
                $Mutasi_benda_approval_model_eloquent = Mutasi_benda_approval_model_eloquent::find($mutasi_benda_approval_id);
                $Mutasi_benda_approval_model_eloquent->status =  '-1';
                $Mutasi_benda_approval_model_eloquent->save();
                $afterReject =  $this->MutasiRepositoryStatus->afterReject($dataMutasiDep->mutasi_benda_id);
                if ($afterReject) {
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
}
