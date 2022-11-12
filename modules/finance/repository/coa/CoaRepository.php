<?php

namespace Modules\finance\repository\coa;

use Modules\finance\models\Coa_model_eloquent;
use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\finance\models\Coa_group_model_eloquent;


class CoaRepository
{

    protected $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function form($data)
    {
        $form = [
            array(
                'id' => 'fin_coa_id',
                'type' => 'hidden',
                'value' => ($data) ? $data->fin_coa_id : '',
            ),
            array(
                'id' => 'fin_coa_group_id',
                'value' => ($data) ? $data->fin_coa_group_id : '',
                'label' => 'Group',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2',
                'options' => $this->CI->m_master->get_dropdown('(select *,concat(fin_coa_group_code," - ",fin_coa_group_name) as codeShow from fin_coa_group)xx', 'fin_coa_group_id', 'codeShow'),
            ),
            array(
                'id' => 'fin_coa_code',
                'value' => ($data) ? $data->fin_coa_code : '',
                'label' => 'Code',
                'form_control_class' => 'col-md-4',
                'placeholder' => 'Automated by system',
                'readonly' => 'true',
            ),
            array(
                'id' => 'fin_coa_name',
                'value' => ($data) ? $data->fin_coa_name : '',
                'label' => 'Name',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'type',
                'value' => ($data) ? $data->type : '',
                'label' => 'Type',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2',
                'options' => ['D' => 'Debit', 'C' => 'Credit'],
            ),
            array(
                'id' => 'status',
                'value' => ($data) ? $data->status : '',
                'label' => 'Status',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2',
                'options' => ['A' => 'Y', 'T' => 'N'],
            ),
            array(
                'id' => 'desc',
                'type' => 'textarea',
                'value' => ($data) ? $data->desc : '',
                'label' => 'Description',
                'rows' => 2,
                'form_control_class' => 'col-md-5',
            ),
        ];

        // if ($data) {
        //     $form[1]['disabled'] = 'true';
        // }

        return $form;
    }

    public function save($dataPost)
    {
        $data = $dataPost;
        if (!$data['fin_coa_id']) {
            // get code
            $set = $this->getCode($data['fin_coa_group_id']);
            $data['fin_coa_code'] = $set['code'];
            $data['fin_coa_code_inc'] = $set['inc'];
            $data['created_at'] =  Date('Y-m-d H:i:s');
            $data['created_by'] =  $this->CI->data['user']->id;
            $this->CI->db->insert($this->CI->table, $data);
        } else {
            $data['updated_at'] =  Date('Y-m-d H:i:s');
            $data['updated_by'] =  $this->CI->data['user']->id;
            $this->CI->db->where('fin_coa_id', $data['fin_coa_id']);
            $this->CI->db->update($this->CI->table, $data);
        }

        $return = array('message' => sprintf(lang('save_success'), lang('heading') . ' ' . $data['fin_coa_name']), 'status' => 'success', 'redirect' => $this->CI->data['module_url']);

        return $return;
    }

    public function getCode($fin_coa_group_id)
    {
        $get = Coa_model_eloquent::where('fin_coa_group_id', $fin_coa_group_id);
        $count = $get->count();
        $get2 = Coa_group_model_eloquent::find($fin_coa_group_id);
        $prefix = $get2->fin_coa_group_code;
        $string = ($count == 0) ? '00' : Coa_model_eloquent::where('fin_coa_group_id', $fin_coa_group_id)->orderByDesc('fin_coa_code_inc')->limit(1)->first()->fin_coa_code_inc; //the last entry from the database

        $strNumber = $string;

        $strNumberNew = str_pad(intval($strNumber) + 1, 2, 0, STR_PAD_LEFT); //increment the number by 1 and pad with 0 in left.
        $new = $prefix . $strNumberNew;

        return ['code' => $new, 'inc' => $strNumberNew];
    }

    public function datatable($start, $length, $filter, $order, $tableParam)
    {
        $columns = isset($tableParam) ? $tableParam['columns'] : [];
        $datas = new Coa_model_eloquent;
        $datas = $datas->join('fin_coa_group', 'fin_coa_group.fin_coa_group_id', '=', 'fin_coa.fin_coa_group_id')
            ->join('fin_coa_aktiva_passiva_sub', 'fin_coa_aktiva_passiva_sub.fin_coa_aktiva_passiva_sub_id', '=', 'fin_coa_group.fin_coa_aktiva_passiva_sub_id')
            ->join('fin_coa_aktiva_passiva', 'fin_coa_aktiva_passiva.fin_coa_aktiva_passiva_id', '=', 'fin_coa_aktiva_passiva_sub.fin_coa_aktiva_passiva_id')
            ->select(
                'fin_coa.fin_coa_id',
                'fin_coa_group.fin_coa_group_code',
                'fin_coa_group.fin_coa_group_name',
                'fin_coa.fin_coa_code',
                'fin_coa.fin_coa_name',
                'fin_coa.type',
                'fin_coa.status',
                'fin_coa.created_at',
                'fin_coa.created_by',
                'fin_coa_aktiva_passiva_sub.name as NameCoaPassivaSub',
                'fin_coa_aktiva_passiva.name as NameCoaAktivaPassiva',
                'fin_coa_aktiva_passiva.fin_coa_aktiva_passiva_id',
            );
        if ($filter) {
            $datas = $datas->where(function ($query) use ($filter, $columns) {
                foreach ($filter as $column => $value) {
                    if ($columns[$column]['filter']) {

                        if (!empty($value)) {
                            if ($columns[$column]['filter']['type'] == 'dropdown') {
                                $query->where($columns[$column]['name'], $value);
                            } else {
                                $query->where($columns[$column]['name'], 'like', '' . $value . '%');
                            }
                        }
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
        return ['dataRaw' => $datas, 'recordsTotal' => Coa_model_eloquent::count(), 'recordsFiltered' => $countFiltered];
    }

    public function setOutputDatatable($get_data, $draw)
    {
        $output = $get_data;
        $output['data'] = array();
        if (count($get_data['dataRaw']) > 0) {
            $dataRaw = $get_data['dataRaw']->toArray();
            for ($i = 0; $i < count($dataRaw); $i++) {
                $payload = array(
                    'id' => $dataRaw[$i]['fin_coa_id']
                );
                $encry = get_jwt_encryption($payload);
                $fin_coa_aktiva_passiva_id = $dataRaw[$i]['fin_coa_aktiva_passiva_id'];
                $output['data'][] = array(
                    $dataRaw[$i]['fin_coa_id'],
                    $dataRaw[$i]['fin_coa_group_code'] . ' - ' . $dataRaw[$i]['fin_coa_group_name'],
                    $dataRaw[$i]['fin_coa_code'],
                    $dataRaw[$i]['fin_coa_name'],
                    $dataRaw[$i]['type'],
                    $dataRaw[$i]['status'],
                    $dataRaw[$i]['NameCoaAktivaPassiva'] . ' - ' . $dataRaw[$i]['NameCoaPassivaSub'],
                    get_date_time($dataRaw[$i]['created_at']) . ' <br/>' . $this->CI->m_master->get_username_by($dataRaw[$i]['created_by']),
                    '<div class = "action-buttons">' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/edit') ? '<a class="' . lang('button_edit_class') . '" title="' . lang('edit') . '" href="' . $this->CI->data['module_url'] . 'form/' . $encry . '">' . lang('button_edit') . '</a>' : '') .
                        '&nbsp  ' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/saldo') ? '<a class="ace-icon fa fa-money bigger-130"  href="' . $this->CI->data['module_url'] . 'saldo/' . $encry . '">' . '' . '</a>' : '') .
                        '&nbsp  ' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/delete') ? '<a tabindex="-1" title="' . lang('delete') . '" class="' . lang('button_delete_class') . ' delete_row_default" href="' . $this->CI->data['module_url'] . 'delete/' . $encry . '">' . lang('button_delete') . '</a>' : '') .
                        '</div>'
                );
            }
        }
        $output['draw'] = $draw++;

        return $output;
    }

    public function delete($id)
    {
        Capsule::beginTransaction();
        try {
            $data = Coa_model_eloquent::find($id);

            $data->delete();

            Capsule::commit();
            $return = ['message' => sprintf(lang('delete_success'), lang('heading')), 'status' => 'success'];
        } catch (\Throwable $th) {
            Capsule::rollback();
            $return = array('message' => $th->getMessage(), 'status' => 'error');
        }

        return $return;
    }
}
