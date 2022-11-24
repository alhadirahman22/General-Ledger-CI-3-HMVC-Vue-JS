<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\finance\models\Coa_group_model_eloquent;
use Modules\finance\models\Coa_model_eloquent;
use Modules\finance\models\Coa_saldo_model_eloquent;
use Modules\finance\models\Coa_saldo_history_model_eloquent;
use Modules\finance\models\GL_model_eloquent;


class Neraca_saldo extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'report/neraca_saldo';
        $this->aauth->control($this->perm);
        $this->data['menu'] = 'report/neraca_saldo';
        $this->data['module_url'] = site_url('report/neraca_saldo/');
    }

    public function index()
    {
        $this->load->library('form_builder');
        $form = [
            array(
                'id' => 'date1',
                'value' => date('Y-m-d'),
                // 'type' => 'text',
                'label' => 'Date 1',
                'class' => 'date',
                'required' => 'true',
                'form_control_class' => 'col-md-4',
                'readonly' => true,
            ),

            array(
                'id' => 'date2',
                'value' => date('Y-m-d'),
                // 'type' => 'text',
                'label' => 'Date 2',
                'required' => 'true',
                'class' => 'date',
                'form_control_class' => 'col-md-4',
                'readonly' => true,
            ),
        ];


        $this->data['form'] = [
            'action' => $this->data['module_url'] . 'submit',
            'build' => $this->form_builder->build_form_horizontal($form),
            'class' => 'no-ajax',
            'class_btn_submit' => 'btn btn-primary',
            'class_btn_submit_text' => 'Submit'
        ];

        $this->data['data'] = [];

        $this->template->_init();
        $this->template->form();

        $this->data['headingOverwrite'] = 'Report Neraca Saldo';
        $this->load->view('default/form', $this->data);
    }

    public function submit()
    {
        // $this->input->is_ajax_request() or exit('No direct post submit allowed!');
        $this->load->library('form_validation');


        $_POST = $this->m_master->form_set_all_trim($_POST);
        $_POST['date1'] = $_POST['date1'];
        $_POST['date2'] = $_POST['date2'];

        $form_validation_arr = array(
            array(
                'field' => 'date1',
                'label' => 'Date 1',
                'rules' => 'required',
            ),
            array(
                'field' => 'date2',
                'label' => 'Date 2',
                'rules' => 'required',
            ),
        );
        $this->form_validation->set_rules($form_validation_arr);
        if ($this->form_validation->run() === true) {
            $dataArray = [];
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
            // get coa_aktiva_passiva
            $dataAktiva = $this->db->get('fin_coa_aktiva_passiva')->result_array();
            for ($i = 0; $i < count($dataAktiva); $i++) {
                $dataTemp1 = [
                    'fin_coa_aktiva_passiva_id' => $dataAktiva[$i]['fin_coa_aktiva_passiva_id'],
                    'name' => $dataAktiva[$i]['name'],
                    'sub' => [],
                ];

                $sub = [];

                $dataSub = $this->db->where('fin_coa_aktiva_passiva_id', $dataTemp1['fin_coa_aktiva_passiva_id'])->get('fin_coa_aktiva_passiva_sub')->result_array();

                for ($j = 0; $j < count($dataSub); $j++) {
                    $temp1 = [
                        'fin_coa_aktiva_passiva_sub_id' => $dataSub[$j]['fin_coa_aktiva_passiva_sub_id'],
                        'name' => $dataSub[$j]['name'],
                        'group' => [],
                    ];

                    $group = Coa_group_model_eloquent::where('fin_coa_aktiva_passiva_sub_id', $temp1['fin_coa_aktiva_passiva_sub_id'])->with('coa')
                        ->whereHas('coa', function ($q) {
                            $q->where('status', 'A');
                        })
                        ->get()->toArray();

                    if (count($group) > 0) {
                        for ($k = 0; $k < count($group); $k++) {

                            $coa_data = $group[$k]['coa'];

                            for ($l = 0; $l < count($coa_data); $l++) {
                                $fin_coa_id = $group[$k]['coa'][$l]['fin_coa_id'];
                                $result = 0;
                                $dateTrans = null;
                                // get result
                                $resultX = Coa_saldo_history_model_eloquent::whereHas('coa_saldo.coa', function ($q) use ($fin_coa_id) {
                                    $q->where('fin_coa_id', $fin_coa_id);
                                })->where('date_trans', '>=', $date1)->where('date_trans', '<=', $date2)->orderBy('fin_coa_saldo_history_id', 'desc')->limit(1)->first();

                                if ($resultX) {
                                    $result = $resultX->become_value;
                                    $dateTrans = $resultX->date_trans;
                                }

                                $group[$k]['coa'][$l]['saldo'] = $result;
                                $group[$k]['coa'][$l]['date_trans'] = $dateTrans;
                            }
                        }

                        $temp1['group'] = $group;

                        $sub[] = $temp1;
                    }
                }

                $dataTemp1['sub'] = $sub;
                $dataArray[] = $dataTemp1;
            }

            $this->printPDF($dataArray, $date1, $date2);
        } else {
            $return = array('message' => validation_errors(), 'status' => 'error');
        }

        if (isset($return['redirect'])) {
            $this->session->set_flashdata('form_response_status', $return['status']);
            $this->session->set_flashdata('form_response_message', $return['message']);
        }
        echo json_encode($return);
    }

    public function printPDF($dataArray, $date1, $date2)
    {
        $this->load->library('pdf');
        $this->load->library('pdf_mc_table');

        $dateFilename = date('Ymd', strtotime($date1)) . '-' . date('Ymd', strtotime($date2));
        $filename = (str_replace('/', '-', 'NeracaSaldo' . $dateFilename)) . '.pdf';

        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        $fpdf->SetMargins(10, 10, 10, 10);
        $fpdf->AddPage();

        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->Cell(0, 10, 'Neraca Saldo', 0, 1, 'C', 0);
        $fpdf->Cell(0, 10, 'Tanggal : ' . get_date($date1) . ' sd ' . get_date($date2), 0, 1, 'C', 0);

        for ($i = 0; $i < count($dataArray); $i++) {
            $fpdf->SetFont('Arial', 'B', 10);
            $fpdf->SetX(10);
            $fpdf->Cell(20, 5, $dataArray[$i]['name'], 0, 1, 'l', 0);
            $totalAktivaPassiva = 0;

            $sub = $dataArray[$i]['sub'];
            for ($j = 0; $j < count($sub); $j++) {

                if ($fpdf->GetY() > 200) { // need logic to page break
                    $fpdf->SetAutoPageBreak(false);
                    $fpdf->AddPage();
                    // $fpdf->SetMargins(10, 10, 10, 10);
                    $fpdf->hPosX = 10;
                    $this->hPosY = 5;
                }

                $fpdf->SetFont('Arial', 'B', 10);
                $fpdf->SetX(15);
                $fpdf->Cell(20, 5, $sub[$j]['name'], 0, 1, 'l', 0);
                $fpdf->SetX(20);
                $group = $sub[$j]['group'];

                for ($k = 0; $k < count($group); $k++) {

                    // if ($fpdf->GetY() > 220) { // need logic to page break
                    //     $fpdf->SetAutoPageBreak(false);
                    //     $fpdf->AddPage();
                    //     // $fpdf->SetMargins(10, 10, 10, 10);
                    //     $fpdf->hPosX = 10;
                    //     $this->hPosY = 5;
                    // }

                    $fpdf->SetX(25);
                    $fpdf->SetFont('Arial', 'B', 10);
                    $fpdf->Cell(20, 5, $group[$k]['fin_coa_group_name'], 0, 1, 'l', 0);

                    $border = 1;
                    $w_1 = 60;
                    $w_2 = 35;

                    $h = 7;
                    $fpdf->SetFillColor(255, 255, 255);

                    $fpdf->SetWidths(array($w_1, $w_2));
                    $fpdf->SetLineHeight($h);


                    $fpdf->SetFont('Arial', 'B', 8);
                    $fpdf->SetAligns(array('C', 'C'));
                    $fpdf->SetX(30);
                    $fpdf->Row(array(
                        'Coa',
                        'Saldo',
                    ));

                    $coaData = $group[$k]['coa'];
                    $fpdf->SetFont('Arial', '', 8);
                    $fpdf->SetAligns(array('L', 'C'));

                    for ($l = 0; $l < count($coaData); $l++) {
                        if ($fpdf->GetY() > 240) { // need logic to page break
                            $fpdf->SetAutoPageBreak(false);
                            $fpdf->AddPage();
                            // $fpdf->SetMargins(10, 10, 10, 10);
                            $fpdf->hPosX = 10;
                            $this->hPosY = 5;
                        }

                        $fpdf->SetX(30);
                        $fpdf->Row(array(
                            $coaData[$l]['fin_coa_code'] . ' - ' . $coaData[$l]['fin_coa_name'],
                            number(abs($coaData[$l]['saldo'])),
                        ));
                        $totalAktivaPassiva += $coaData[$l]['saldo'];
                    }

                    $y = $fpdf->GetY();
                    $fpdf->SetY($y + 5);
                }

                $y = $fpdf->GetY();
                $fpdf->SetY($y + 5);
            }

            $fpdf->SetFont('Arial', 'B', 10);
            $fpdf->Cell(20, 5, 'Total ' . $dataArray[$i]['name'] . ' : ' . number($totalAktivaPassiva), 0, 1, 'l', 0);
            $y = $fpdf->GetY();
            $fpdf->SetY($y + 5);
        }

        $fpdf->Output('I', $filename,);
    }
}
