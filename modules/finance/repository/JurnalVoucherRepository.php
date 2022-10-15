<?php

namespace Modules\finance\repository;

use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\finance\models\Jurnal_voucher_model_eloquent;

class JurnalVoucherRepository
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
                'id' => 'fin_jurnal_voucher_id',
                'type' => 'hidden',
                'value' => ($data) ? $data->fin_jurnal_voucher_id : '',
            ),
            array(
                'id' => 'fin_jurnal_voucher_code',
                'value' => ($data) ? $data->fin_jurnal_voucher_code : '',
                'label' => ($data) ? 'Code' : 'Prefix Code',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'fin_jurnal_voucher_name',
                'value' => ($data) ? $data->fin_jurnal_voucher_name : '',
                'label' => 'Name',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
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

        if ($data) {
            $form[1]['readonly'] = 'true';
        }

        return $form;
    }

    public function save($dataPost)
    {
        $data = $dataPost;
        if (!$data['fin_jurnal_voucher_id']) {
            // get code
            $data['fin_jurnal_voucher_prefix'] =  $data['fin_jurnal_voucher_code'];
            $set = $this->getCode($data['fin_jurnal_voucher_prefix']);
            $data['fin_jurnal_voucher_code'] = $set['code'];
            $data['fin_jurnal_voucher_inc'] = $set['inc'];
            $data['created_at'] =  Date('Y-m-d H:i:s');
            $data['created_by'] =  $this->CI->data['user']->id;
            $this->CI->db->insert($this->CI->table, $data);
        } else {
            $data['updated_at'] =  Date('Y-m-d H:i:s');
            $data['updated_by'] =  $this->CI->data['user']->id;
            $this->CI->db->where('fin_jurnal_voucher_id', $data['fin_jurnal_voucher_id']);
            $this->CI->db->update($this->CI->table, $data);
        }

        $return = array('message' => sprintf(lang('save_success'), lang('heading') . ' ' . $data['fin_jurnal_voucher_name']), 'status' => 'success', 'redirect' => $this->CI->data['module_url']);

        return $return;
    }

    public function getCode($prefix)
    {
        $count = Jurnal_voucher_model_eloquent::where('fin_jurnal_voucher_prefix', $prefix)->count();
        $string = ($count == 0) ? '00' : Jurnal_voucher_model_eloquent::where('fin_jurnal_voucher_prefix', $prefix)->orderByDesc('fin_jurnal_voucher_inc')->limit(1)->first()->fin_jurnal_voucher_inc; //the last entry from the database

        $strNumber = $string;

        $strNumberNew = str_pad(intval($strNumber) + 1, 2, 0, STR_PAD_LEFT); //increment the number by 1 and pad with 0 in left.
        $new = $prefix . $strNumberNew;

        return ['code' => $new, 'inc' => $strNumberNew];
    }

    public function datatable($start, $length, $filter, $order, $tableParam)
    {
        $columns = isset($tableParam) ? $tableParam['columns'] : [];
        $datas = new Jurnal_voucher_model_eloquent;

        if ($filter) {
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
        return ['dataRaw' => $datas, 'recordsTotal' => Jurnal_voucher_model_eloquent::count(), 'recordsFiltered' => $countFiltered];
    }

    public function setOutputDatatable($get_data, $draw)
    {
        $output = $get_data;
        $output['data'] = array();
        if (count($get_data['dataRaw']) > 0) {
            $dataRaw = $get_data['dataRaw']->toArray();
            for ($i = 0; $i < count($dataRaw); $i++) {
                $payload = array(
                    'id' => $dataRaw[$i]['fin_jurnal_voucher_id']
                );
                $encry = get_jwt_encryption($payload);
                $output['data'][] = array(
                    $dataRaw[$i]['fin_jurnal_voucher_id'],
                    $dataRaw[$i]['fin_jurnal_voucher_code'],
                    $dataRaw[$i]['fin_jurnal_voucher_name'],
                    get_date_time($dataRaw[$i]['created_at']) . ' <br/>' . $this->CI->m_master->get_username_by($dataRaw[$i]['created_by']),
                    '<div class = "action-buttons">' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/edit') ? '<a class="' . lang('button_edit_class') . '" title="' . lang('edit') . '" href="' . $this->CI->data['module_url'] . 'form/' . $encry . '">' . lang('button_edit') . '</a>' : '') .
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
            $data = Jurnal_voucher_model_eloquent::find($id);

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
