<?php

namespace Repository\mutasi;

use Repository\mutasi\JenisMutasiRepository;
use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\mutasi\models\Jenis_mutasi_department_model_eloquent;
use Modules\mutasi\models\Jenis_mutasi_department_approval_model_eloquent;

class JenisMutasiDepartmentRepository
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
        $datas = new Jenis_mutasi_department_model_eloquent;
        $datas = $datas->join('jenis_mutasi', 'jenis_mutasi.jenis_mutasi_id', '=', 'jenis_mutasi_department.jenis_mutasi_id')
            ->join('departments', 'departments.department_id', '=', 'jenis_mutasi_department.department_id')
            ->select(
                'jenis_mutasi_department.jenis_mutasi_department_id',
                'jenis_mutasi_department.jenis_mutasi_id',
                'jenis_mutasi.type_approval',
                'jenis_mutasi.name',
                Capsule::raw('(SELECT GROUP_CONCAT(DISTINCT departments.`name` ORDER BY departments.department_id ASC SEPARATOR ", ") FROM departments
                         join jenis_mutasi_department as kd on kd.department_id = departments.department_id
                        where     kd.jenis_mutasi_id = jenis_mutasi_department.jenis_mutasi_id) as tag_department')
            )
            ->whereRaw('jenis_mutasi.deleted_at is NULL')
            ->groupby('jenis_mutasi_department.jenis_mutasi_id');
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
        return ['dataRaw' => $datas, 'recordsTotal' => Jenis_mutasi_department_model_eloquent::count(), 'recordsFiltered' => $countFiltered];
    }

    public function setOutputDatatable($get_data, $draw)
    {
        $output = $get_data;
        $output['data'] = array();
        if (count($get_data['dataRaw']) > 0) {
            $dataRaw = $get_data['dataRaw']->toArray();
            for ($i = 0; $i < count($dataRaw); $i++) {
                $output['data'][] = array(
                    $dataRaw[$i]['jenis_mutasi_department_id'],
                    $dataRaw[$i]['name'],
                    $dataRaw[$i]['tag_department'],
                    $this->type_approval[$dataRaw[$i]['type_approval']],
                    '<div class = "hidden-sm hidden-xs action-buttons">' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/add') ? '<a class="' . lang('button_view_class') . '" title="' . lang('view') . '" href="' . $this->CI->data['module_url'] . 'form/' . $dataRaw[$i]['jenis_mutasi_id'] . '">' . lang('button_view') . '</a>' : '') .
                        '&nbsp  ' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/delete') ? '<a tabindex="-1" title="' . lang('delete') . '" class="' . lang('button_delete_class') . ' delete_row_default" href="' . $this->CI->data['module_url'] . 'delete/' . $dataRaw[$i]['jenis_mutasi_id'] . '">' . lang('button_delete') . '</a>' : '') .
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

        $jenis_mutasi_department = $dataPost['jenis_mutasi_department'];

        // check data already added or not
        $jenis_mutasi_id = $jenis_mutasi_department['jenis_mutasi_id'];
        $count = Jenis_mutasi_department_model_eloquent::where('jenis_mutasi_id', $jenis_mutasi_id)->whereHas('jenis_mutasi', function ($query)  use ($jenis_mutasi_id) {
            $query->where('jenis_mutasi_id', $jenis_mutasi_id);
        })->count();

        if ($count > 0) {
            return $rs = ['message' => 'Jenis Mutasi telah ditambahkan di list, mohon pilih yang lain', 'status' => 'error'];
        }

        if (empty($jenis_mutasi_department['jenis_mutasi_id'])) {
            return $rs = ['message' => 'Mohon Pilih Jenis Mutasi ', 'status' => 'error'];
        }

        if (count($jenis_mutasi_department['department_id']) == 0) {
            return $rs = ['message' => 'Mohon Pilih Department ', 'status' => 'error'];
        }

        $jenis_mutasi_department_approval = $dataPost['jenis_mutasi_department_approval'];
        if (count($jenis_mutasi_department_approval) == 0) {
            return $rs = ['message' => 'Mohon Add Jabatan ', 'status' => 'error'];
        }

        for ($i = 0; $i < count($jenis_mutasi_department_approval); $i++) {
            $jabatan = $jenis_mutasi_department_approval[$i]['jabatan'];
            if (count($jabatan) == 0) {
                return $rs = ['message' => 'Mohon Add Jabatan ', 'status' => 'error'];
            }

            for ($j = 0; $j < count($jabatan); $j++) {
                $jabatan_id = $jabatan[$j]['jabatan_id'];

                if (empty($jabatan_id)) {
                    return $rs = ['message' => 'Mohon pilih Jabatan ', 'status' => 'error'];
                }
            }

            $typeApproval = $jenis_mutasi_department_approval[$i]['typeApproval'];
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
            $jenis_mutasi_id = $dataPost['jenis_mutasi_department']['jenis_mutasi_id'];
            $jenis_mutasi_department_approval = $dataPost['jenis_mutasi_department_approval'];
            for ($i = 0; $i < count($jenis_mutasi_department_approval); $i++) {

                $Jenis_mutasi_department_model_eloquent =  new Jenis_mutasi_department_model_eloquent;
                $department_id = $jenis_mutasi_department_approval[$i]['department_id'];
                $type_approval = $jenis_mutasi_department_approval[$i]['typeApproval'];

                $Jenis_mutasi_department_model_eloquent->department_id = $department_id;
                $Jenis_mutasi_department_model_eloquent->type_approval = $type_approval;
                $Jenis_mutasi_department_model_eloquent->jenis_mutasi_id = $jenis_mutasi_id;
                $Jenis_mutasi_department_model_eloquent->save();

                $jenis_mutasi_department_id = $Jenis_mutasi_department_model_eloquent->jenis_mutasi_department_id;
                $jabatan = $jenis_mutasi_department_approval[$i]['jabatan'];

                for ($j = 0; $j < count($jabatan); $j++) {
                    $jabatan_id = $jabatan[$j]['jabatan_id'];
                    $Jenis_mutasi_department_approval_model_eloquent = new Jenis_mutasi_department_approval_model_eloquent;
                    $Jenis_mutasi_department_approval_model_eloquent->jenis_mutasi_department_id = $jenis_mutasi_department_id;
                    $Jenis_mutasi_department_approval_model_eloquent->jabatan_id = $jabatan_id;
                    $Jenis_mutasi_department_approval_model_eloquent->save();
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

    public function delete($jenis_mutasi_id)
    {
        Capsule::beginTransaction();
        try {
            $KlasifikasiRepository =  new JenisMutasiRepository();
            $data =  $KlasifikasiRepository->findByID($jenis_mutasi_id)->toArray();
            // delete klasifikasi_department_approval
            $tagging_department = $data['tagging_department'];
            for ($i = 0; $i < count($tagging_department); $i++) {
                $jenis_mutasi_department_id = $tagging_department[$i]['pivot']['jenis_mutasi_department_id'];
                $Jenis_mutasi_department_model_eloquent =  Jenis_mutasi_department_model_eloquent::find($jenis_mutasi_department_id);
                $Jenis_mutasi_department_model_eloquent->jabatan()->delete();
                $Jenis_mutasi_department_model_eloquent->delete();
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
