<?php

namespace Repository\master;

use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\master\models\Klasifikasi_department_model_eloquent;
use Modules\master\models\Klasifikasi_department_approval_model_eloquent;
use Repository\master\KlasifikasiRepository;

class KlasifikasiDepartmentRepository
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
        $datas = new Klasifikasi_department_model_eloquent;
        $datas = $datas->join('klasifikasi', 'klasifikasi.klasifikasi_id', '=', 'klasifikasi_department.klasifikasi_id')
            ->join('departments', 'departments.department_id', '=', 'klasifikasi_department.department_id')
            ->select(
                'klasifikasi_department.klasifikasi_department_id',
                'klasifikasi_department.klasifikasi_id',
                'klasifikasi.type_approval',
                'klasifikasi.name',
                Capsule::raw('(SELECT GROUP_CONCAT(DISTINCT departments.`name` ORDER BY departments.department_id ASC SEPARATOR ", ") FROM departments
                         join klasifikasi_department as kd on kd.department_id = departments.department_id
                        where     kd.klasifikasi_id = klasifikasi_department.klasifikasi_id) as tag_department')
            )
            ->groupby('klasifikasi_department.klasifikasi_id');
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
        return ['dataRaw' => $datas, 'recordsTotal' => Klasifikasi_department_model_eloquent::count(), 'recordsFiltered' => $countFiltered];
    }

    public function setOutputDatatable($get_data, $draw)
    {
        $output = $get_data;
        $output['data'] = array();
        if (count($get_data['dataRaw']) > 0) {
            $dataRaw = $get_data['dataRaw']->toArray();
            for ($i = 0; $i < count($dataRaw); $i++) {
                $output['data'][] = array(
                    $dataRaw[$i]['klasifikasi_department_id'],
                    $dataRaw[$i]['name'],
                    $dataRaw[$i]['tag_department'],
                    $this->type_approval[$dataRaw[$i]['type_approval']],
                    '<div class = "hidden-sm hidden-xs action-buttons">' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/edit') ? '<a class="' . lang('button_view_class') . '" title="' . lang('view') . '" href="' . $this->CI->data['module_url'] . 'form/' . $dataRaw[$i]['klasifikasi_id'] . '">' . lang('button_view') . '</a>' : '') .
                        '&nbsp  ' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/delete') ? '<a tabindex="-1" title="' . lang('delete') . '" class="' . lang('button_delete_class') . ' delete_row_default" href="' . $this->CI->data['module_url'] . 'delete/' . $dataRaw[$i]['klasifikasi_id'] . '">' . lang('button_delete') . '</a>' : '') .
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

        $klasifikasi_department = $dataPost['klasifikasi_department'];

        if (empty($klasifikasi_department['klasifikasi_id'])) {
            return $rs = ['message' => 'Mohon Pilih Klasifikasi ', 'status' => 'error'];
        }

        if (count($klasifikasi_department['department_id']) == 0) {
            return $rs = ['message' => 'Mohon Pilih Department ', 'status' => 'error'];
        }

        $klasifikasi_department_approval = $dataPost['klasifikasi_department_approval'];
        if (count($klasifikasi_department_approval) == 0) {
            return $rs = ['message' => 'Mohon Add Jabatan ', 'status' => 'error'];
        }

        for ($i = 0; $i < count($klasifikasi_department_approval); $i++) {
            $jabatan = $klasifikasi_department_approval[$i]['jabatan'];
            if (count($jabatan) == 0) {
                return $rs = ['message' => 'Mohon Add Jabatan ', 'status' => 'error'];
            }

            for ($j = 0; $j < count($jabatan); $j++) {
                $jabatan_id = $jabatan[$j]['jabatan_id'];

                if (empty($jabatan_id)) {
                    return $rs = ['message' => 'Mohon pilih Jabatan ', 'status' => 'error'];
                }
            }

            $typeApproval = $klasifikasi_department_approval[$i]['typeApproval'];
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
            $klasifikasi_id = $dataPost['klasifikasi_department']['klasifikasi_id'];
            $klasifikasi_department_approval = $dataPost['klasifikasi_department_approval'];
            for ($i = 0; $i < count($klasifikasi_department_approval); $i++) {
                $Klasifikasi_department_model_eloquent =  new Klasifikasi_department_model_eloquent();
                $department_id = $klasifikasi_department_approval[$i]['department_id'];
                $type_approval = $klasifikasi_department_approval[$i]['typeApproval'];

                $Klasifikasi_department_model_eloquent->department_id = $department_id;
                $Klasifikasi_department_model_eloquent->type_approval = $type_approval;
                $Klasifikasi_department_model_eloquent->klasifikasi_id = $klasifikasi_id;
                $Klasifikasi_department_model_eloquent->save();

                $klasifikasi_department_id = $Klasifikasi_department_model_eloquent->klasifikasi_department_id;
                $jabatan = $klasifikasi_department_approval[$i]['jabatan'];

                for ($j = 0; $j < count($jabatan); $j++) {
                    $jabatan_id = $jabatan[$j]['jabatan_id'];
                    $Klasifikasi_department_model_eloquent = new Klasifikasi_department_approval_model_eloquent;
                    $Klasifikasi_department_model_eloquent->klasifikasi_department_id = $klasifikasi_department_id;
                    $Klasifikasi_department_model_eloquent->jabatan_id = $jabatan_id;
                    $Klasifikasi_department_model_eloquent->save();
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

    public function delete($klasifikasi_id)
    {
        Capsule::beginTransaction();
        try {
            $KlasifikasiRepository =  new KlasifikasiRepository();
            $data =  $KlasifikasiRepository->findByID($klasifikasi_id)->toArray();
            // delete klasifikasi_department_approval
            $tagging_department = $data['tagging_department'];
            for ($i = 0; $i < count($tagging_department); $i++) {
                $klasifikasi_department_id = $tagging_department[$i]['pivot']['klasifikasi_department_id'];
                $Klasifikasi_department_model_eloquent =  Klasifikasi_department_model_eloquent::find($klasifikasi_department_id);
                $Klasifikasi_department_model_eloquent->jabatan()->delete();
                $Klasifikasi_department_model_eloquent->delete();
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
