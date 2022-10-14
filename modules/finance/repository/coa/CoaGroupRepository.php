<?php

namespace Modules\finance\repository\coa;

use Modules\finance\models\Coa_group_model_eloquent;

class CoaGroupRepository
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
                'id' => 'fin_coa_group_id',
                'type' => 'hidden',
                'value' => ($data) ? $data->fin_coa_group_id : '',
            ),
            array(
                'id' => 'fin_coa_group_code',
                'value' => ($data) ? $data->fin_coa_group_code : '',
                'label' => ($data) ? 'Code' : 'Prefix Code',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
            ),
            array(
                'id' => 'fin_coa_group_name',
                'value' => ($data) ? $data->fin_coa_group_name : '',
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
            array(
                'id' => 'fin_coa_aktiva_passiva_sub_id',
                'value' => ($data) ? $data->fin_coa_aktiva_passiva_sub_id : '',
                'label' => 'Group',
                'required' => 'true',
                'type' => 'dropdown',
                'form_control_class' => 'col-md-4',
                'class' => 'select2',
                'options' => $this->CI->m_master->get_dropdown('(select b.*,concat(a.`name`," - ",b.`name`) as codeShow from fin_coa_aktiva_passiva as a join fin_coa_aktiva_passiva_sub as b on a.fin_coa_aktiva_passiva_id = b.fin_coa_aktiva_passiva_id )xx', 'fin_coa_aktiva_passiva_sub_id', 'codeShow', false, '', true),
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
        if (!$data['fin_coa_group_id']) {
            // get code
            $data['fin_coa_group_prefix'] =  $data['fin_coa_group_code'];
            $set = $this->getCode($data['fin_coa_group_prefix']);
            $data['fin_coa_group_code'] = $set['code'];
            $data['fin_coa_group_inc'] = $set['inc'];
            $data['created_at'] =  Date('Y-m-d H:i:s');
            $data['created_by'] =  $this->CI->data['user']->id;
            $this->CI->db->insert($this->CI->table, $data);
        } else {
            $data['updated_at'] =  Date('Y-m-d H:i:s');
            $data['updated_by'] =  $this->CI->data['user']->id;
            $this->db->where('fin_coa_group_id', $data['fin_coa_group_id']);
            $this->db->update($this->table, $data);
        }
        $return = array('message' => sprintf(lang('save_success'), lang('heading') . ' ' . $data['fin_coa_group_name']), 'status' => 'success');

        return $return;
    }

    public function getCode($prefix)
    {
        $count = Coa_group_model_eloquent::where('fin_coa_group_prefix', $prefix)->count();
        $string = ($count == 0) ? '00' : Coa_group_model_eloquent::where('fin_coa_group_prefix', $prefix)->orderByDesc('fin_coa_group_inc')->limit(1)->first()->fin_coa_group_inc; //the last entry from the database

        $strNumber = $string;

        $strNumberNew = str_pad(intval($strNumber) + 1, 2, 0, STR_PAD_LEFT); //increment the number by 1 and pad with 0 in left.
        $new = $prefix . $strNumberNew;

        return ['code' => $new, 'inc' => $strNumberNew];
    }

    public function datatable($start, $length, $filter, $order, $tableParam)
    {
    }

    public function setOutputDatatable($get_data, $draw)
    {
    }
}
