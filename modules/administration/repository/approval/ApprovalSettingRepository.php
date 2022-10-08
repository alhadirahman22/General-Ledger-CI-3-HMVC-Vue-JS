<?php

namespace Modules\administration\repository\approval;

// use Repository\mutasi\JenisMutasiRepository;
use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\administration\models\Approval_rule_model_eloquent;
use Modules\administration\repository\approval\ApprovalRuleRepository;
use Modules\administration\models\Approval_rule_department_model_eloquent;
use Modules\administration\models\Approval_rule_department_model_eloquent2;
use Modules\administration\models\Approval_rule_department_emp_model_eloquent;

class ApprovalSettingRepository
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

    public function datatable($start, $length, $filter, $order, $tableParam)
    {
        $columns = isset($tableParam) ? $tableParam['columns'] : [];
        $datas = new Approval_rule_department_model_eloquent;
        $datas = $datas->join('approval_rule', 'approval_rule.approval_rule_id', '=', 'approval_rule_department.approval_rule_id')
            ->join('departments', 'departments.department_id', '=', 'approval_rule_department.department_id')
            ->select(
                'approval_rule_department.approval_rule_department_id',
                'approval_rule_department.approval_rule_id',
                'approval_rule.type_approval',
                'approval_rule_department.type_approval as type_approval2',
                'approval_rule.name',
                Capsule::raw('(SELECT GROUP_CONCAT(DISTINCT departments.`name` ORDER BY departments.department_id ASC SEPARATOR ", ") FROM departments
                         join approval_rule_department as kd on kd.department_id = departments.department_id
                        where     kd.approval_rule_id = approval_rule_department.approval_rule_id) as tag_department')
            )
            ->whereRaw('approval_rule.deleted_at is NULL')
            ->groupby('approval_rule_department.approval_rule_id');
        // filter
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
            // $datas = $datas->orderBy($order['column'], $order['dir']);
            $datas = $datas->orderByRaw($order['column'] . ' ' . $order['dir']);
        }

        $datas = $datas->offset($start)
            ->limit($length);

        $datas = $datas->get();
        return ['dataRaw' => $datas, 'recordsTotal' => Approval_rule_model_eloquent::count(), 'recordsFiltered' => $countFiltered];
    }

    public function setOutputDatatable($get_data, $draw)
    {
        $output = $get_data;
        $output['data'] = array();
        if (count($get_data['dataRaw']) > 0) {
            $dataRaw = $get_data['dataRaw']->toArray();
            for ($i = 0; $i < count($dataRaw); $i++) {
                $payload = array(
                    'id' => $dataRaw[$i]['approval_rule_id']
                );
                $encry = get_jwt_encryption($payload);
                // $dataRaw[$i]['approval_rule_department_id'],
                // $this->type_approval[$dataRaw[$i]['type_approval']],
                $output['data'][] = array(

                    '<span style="color:blue;font-size:14px;">' . $dataRaw[$i]['name'] . '</span> <br/><span class="label label-info"> Approval Type for Dept : ' . $this->type_approval[$dataRaw[$i]['type_approval']] . '</span>',
                    $dataRaw[$i]['tag_department'],

                    '<div class = "action-buttons">' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/add') ? '<a class="' . lang('button_view_class') . '" title="' . lang('view') . '" href="' . $this->CI->data['module_url'] . 'form/' . $encry . '">' . lang('button_view') . '</a>' : '') .
                        '&nbsp  ' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/delete') ? '<a tabindex="-1" title="' . lang('delete') . '" class="' . lang('button_delete_class') . ' delete_row_default" href="' . $this->CI->data['module_url'] . 'delete/' . $encry . '">' . lang('button_delete') . '</a>' : '') .
                        '</div>'
                );
            }
        }
        $output['draw'] = $draw++;

        return $output;
    }

    public function validationManually($dataPost)
    {
        $rs = ['message' => '', 'status' => 'success'];

        $approval_rule_department = $dataPost['approval_rule_department'];

        // check data already added or not
        $approval_rule_id = $approval_rule_department['approval_rule_id'];
        $count = Approval_rule_department_model_eloquent::where('approval_rule_id', $approval_rule_id)->whereHas('approval_name', function ($query)  use ($approval_rule_id) {
            $query->where('approval_rule_id', $approval_rule_id);
        })->count();

        if ($count > 0) {
            return $rs = ['message' => ' Approval Name telah ditambahkan di list, mohon pilih yang lain', 'status' => 'error'];
        }

        if (empty($approval_rule_department['approval_rule_id'])) {
            return $rs = ['message' => 'Mohon Pilih Approval Name ', 'status' => 'error'];
        }

        if (count($approval_rule_department['department_id']) == 0) {
            return $rs = ['message' => 'Mohon Pilih Department ', 'status' => 'error'];
        }

        $approval_rule_department_approval = $dataPost['approval_rule_department_approval'];
        if (count($approval_rule_department_approval) == 0) {
            return $rs = ['message' => 'Mohon Add Jabatan ', 'status' => 'error'];
        }

        for ($i = 0; $i < count($approval_rule_department_approval); $i++) {
            $jabatan = $approval_rule_department_approval[$i]['jabatan'];
            if (count($jabatan) == 0) {
                return $rs = ['message' => 'Mohon Add Jabatan ', 'status' => 'error'];
            }

            for ($j = 0; $j < count($jabatan); $j++) {
                $jabatan_id = $jabatan[$j]['jabatan_id'];

                if (empty($jabatan_id)) {
                    return $rs = ['message' => 'Mohon pilih Jabatan ', 'status' => 'error'];
                }
            }

            $typeApproval = $approval_rule_department_approval[$i]['typeApproval'];
            if (empty($typeApproval)) {
                return $rs = ['message' => 'Mohon pilih Type Approval ', 'status' => 'error'];
            }
        }

        return $rs;
    }

    public function create($dataPost)
    {

        Capsule::beginTransaction();
        try {
            $approval_rule_id = $dataPost['approval_rule_department']['approval_rule_id'];
            $approval_rule_department_approval = $dataPost['approval_rule_department_approval'];
            for ($i = 0; $i < count($approval_rule_department_approval); $i++) {

                $Approval_rule_department_model_eloquent =  new Approval_rule_department_model_eloquent2;
                $department_id = $approval_rule_department_approval[$i]['department_id'];
                $type_approval = $approval_rule_department_approval[$i]['typeApproval'];

                $Approval_rule_department_model_eloquent->department_id = $department_id;
                $Approval_rule_department_model_eloquent->type_approval = $type_approval;
                $Approval_rule_department_model_eloquent->approval_rule_id = $approval_rule_id;
                $Approval_rule_department_model_eloquent->save();

                $approval_rule_department_id = $Approval_rule_department_model_eloquent->approval_rule_department_id;

                $jabatan = $approval_rule_department_approval[$i]['jabatan'];


                for ($j = 0; $j < count($jabatan); $j++) {
                    $jabatan_id = $jabatan[$j]['jabatan_id'];
                    $Approval_rule_department_emp_model_eloquent = new Approval_rule_department_emp_model_eloquent;
                    $Approval_rule_department_emp_model_eloquent->approval_rule_department_id = $approval_rule_department_id;
                    $Approval_rule_department_emp_model_eloquent->jabatan_id = $jabatan_id;
                    $Approval_rule_department_emp_model_eloquent->save();
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

    public function delete($approval_rule_id)
    {
        Capsule::beginTransaction();
        try {
            $ApprovalRuleRepository =  new ApprovalRuleRepository();
            $data =  $ApprovalRuleRepository->findByID($approval_rule_id)->toArray();
            // delete klasifikasi_department_approval
            $tagging_department = $data['tagging_department'];
            for ($i = 0; $i < count($tagging_department); $i++) {
                $approval_rule_department_id = $tagging_department[$i]['pivot']['approval_rule_department_id'];
                $Approval_rule_department_model_eloquent =  Approval_rule_department_model_eloquent::find($approval_rule_department_id);
                $Approval_rule_department_model_eloquent->jabatan()->delete();
                $Approval_rule_department_model_eloquent->delete();
            }

            Capsule::commit();
            $return = ['message' => '', 'status' => 'success'];
        } catch (\Throwable $th) {
            Capsule::rollback();
            $return = array('message' => $th->getMessage(), 'status' => 'error');
        }

        return $return;
    }
}
