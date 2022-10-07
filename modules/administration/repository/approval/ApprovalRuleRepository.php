<?php

namespace Modules\administration\repository\approval;

use Modules\administration\models\Approval_rule_model_eloquent;

class ApprovalRuleRepository
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
    public function findByID($approval_rule_id)
    {
        $dataApproval = Approval_rule_model_eloquent::with('tagging_department')->where('approval_rule_id', $approval_rule_id)->first();
        if ($dataApproval) {
            $tagging_department = $dataApproval->tagging_department;
            $i = 0;
            $rs = $dataApproval->toArray();
            foreach ($tagging_department as $td) {

                $rs['tagging_department'][$i]['pivot']['jabatan'] = $td->pivot->jabatan->toArray();
                $i++;
            }
        }

        return $dataApproval;
    }

    public function get($where = null)
    {
        $data = new Approval_rule_model_eloquent;
        $data = $data->with('tagging_department');
        if ($where != null) {
            foreach ($where as $key => $value) {
                $data = $data->where($key, $value);
            }
            return $data->get()->toArray();
        } else {
            return $data->get()->toArray();
        }
    }

    public function persetujuan($where = null)
    {
        $data = new Approval_rule_model_eloquent;
        $data = $data->with('tagging_department')->has('tagging_department');
        if ($where != null) {
            foreach ($where as $key => $value) {
                $data = $data->where($key, $value);
            }
            return $data->get()->toArray();
        } else {
            return $data->get()->toArray();
        }
    }

    public function onceUsed($where = null)
    {
        $data = new Approval_rule_model_eloquent;
        $data = $data->doesntHave('tagging_department');

        if ($where != null) {
            foreach ($where as $key => $value) {
                $data = $data->where($key, $value);
            }
            return $data->get()->toArray();
        } else {
            return $data->get()->toArray();
        }
    }

    public function datatable($start, $length, $filter, $order, $tableParam)
    {
        $columns = isset($tableParam) ? $tableParam['columns'] : [];
        $datas = new Approval_rule_model_eloquent;

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

                $output['data'][] = array(
                    $dataRaw[$i]['name'],
                    $this->type_approval[$dataRaw[$i]['type_approval']],
                    get_date_time($dataRaw[$i]['created_at']),
                    $this->CI->m_master->get_username_by($dataRaw[$i]['created_by']),
                    get_date_time($dataRaw[$i]['updated_at']),
                    $this->CI->m_master->get_username_by($dataRaw[$i]['updated_by']),
                    '<div class = " action-buttons">' .
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
}
