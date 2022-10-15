<?php

namespace Modules\finance\repository;

use Modules\finance\models\GL_model_eloquent;

use Illuminate\Database\Capsule\Manager as Capsule;

class GLRepository
{

    protected $CI;
    public static $status = [
        '0' => 'Draft',
        '1' => 'Issued',
    ];
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function datatable($start, $length, $filter, $order, $tableParam)
    {
        $columns = isset($tableParam) ? $tableParam['columns'] : [];
        $datas = new GL_model_eloquent;
        $datas = $datas->with(['detail_taging_coa', 'jurnal']);
        $datas = $datas->where(function ($query) use ($filter, $columns) {
            if ($filter) {
                foreach ($filter as $column => $value) {
                    if ($column == 2) {
                        if (!empty($value)) {
                            $query->where($columns[$column]['name'], $value);
                        }
                    } else {
                        if ($column != 3 && $column != 4) {
                            $query->where($columns[$column]['name'], 'like', '' . $value . '%');
                        }
                    }
                }
            }
        });


        if ($filter) {
            foreach ($filter as $column => $value) {
                if ($column == 3) {
                    $datas = $datas->whereHas('detail_taging_coa', function ($query) use ($value) {
                        $query->where('fin_coa.fin_coa_code', 'like', '' . $value . '%');
                    });
                }

                if ($column == 4) {
                    $datas = $datas->whereHas('detail_taging_coa', function ($query) use ($value) {
                        $query->where('fin_gl_detail.fin_gl_referensi', 'like', '' . $value . '%');
                    });
                }
            }
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
        return ['dataRaw' => $datas, 'recordsTotal' => GL_model_eloquent::count(), 'recordsFiltered' => $countFiltered];
    }

    public function setOutputDatatable($get_data, $draw)
    {
        $output = $get_data;
        $output['data'] = array();
        if (count($get_data['dataRaw']) > 0) {
            $dataRaw = $get_data['dataRaw']->toArray();
            for ($i = 0; $i < count($dataRaw); $i++) {
                $payload = array(
                    'id' => $dataRaw[$i]['fin_gl_id']
                );
                $encry = get_jwt_encryption($payload);

                $detail_taging_coa = $dataRaw[$i]['detail_taging_coa'];

                $collection =  collect($detail_taging_coa);
                $showCoa = $collection->implode('fin_coa_code', ', ');
                $hide = ($dataRaw[$i]['status'] == 1) ? 'hide' : '';
                $showReferensi = $collection->implode('pivot.fin_gl_referensi', ', ');

                $output['data'][] = array(
                    $dataRaw[$i]['fin_gl_id'],
                    $dataRaw[$i]['fin_gl_code'],
                    $dataRaw[$i]['jurnal']['fin_jurnal_voucher_code'] . ' - ' . $dataRaw[$i]['jurnal']['fin_jurnal_voucher_name'],
                    $showCoa,
                    $showReferensi,
                    $dataRaw[$i]['fin_gl_no_bukti'],
                    get_date($dataRaw[$i]['fin_gl_date']),
                    number($dataRaw[$i]['debit_total']),
                    number($dataRaw[$i]['credit_total']),
                    '<div class = "action-buttons">' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/edit') ? '<a class="' . lang('button_edit_class') . '" title="' . lang('edit') . '" href="' . $this->CI->data['module_url'] . 'form/' . $encry . '">' . lang('button_edit') . '</a>' : '') .
                        '&nbsp  ' .
                        ($this->CI->aauth->is_allowed($this->CI->perm . '/delete') ? '<a tabindex="-1" title="' . lang('delete') . '" class="' . lang('button_delete_class') . ' delete_row_default ' . $hide . ' " href="' . $this->CI->data['module_url'] . 'delete/' . $encry . '">' . lang('button_delete') . '</a>' : '') .
                        '</div>'
                );
            }
        }
        $output['draw'] = $draw++;

        return $output;
    }
}
