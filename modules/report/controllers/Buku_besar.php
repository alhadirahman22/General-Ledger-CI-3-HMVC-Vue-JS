<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\finance\models\Coa_saldo_history_model_eloquent;
use Modules\finance\models\GL_model_eloquent;
use Modules\finance\models\Coa_saldo_model_eloquent;


class Buku_besar extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'report/buku_besar';
        $this->aauth->control($this->perm);
        $this->data['menu'] = 'report/buku_besar';
        $this->data['module_url'] = site_url('report/buku_besar/');
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
        ];

        $this->data['data'] = [];

        $this->template->_init();
        $this->template->form();

        $this->data['btn_submit'] = [
            'class' => 'btn-primary',
            'text' => 'Submit'

        ];

        $this->data['headingOverwrite'] = 'Report Buku Besar';
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
            // $data = Coa_saldo_history_model_eloquent::where('date_trans', '>=', $date1)->where('date_trans', '<=', $date2)->groupBy('coa_saldo_id')->get()->toArray();

            $data = Coa_saldo_history_model_eloquent::where('date_trans', '>=', $date1)->where('date_trans', '<=', $date2)
                ->whereHas('coa_saldo.coa', function ($q) {
                    $q->where('status', 'A');
                })
                ->groupBy('fin_coa_saldo_id')->get()->toArray();

            for ($i = 0; $i < count($data); $i++) {
                $fin_coa_saldo_id = $data[$i]['fin_coa_saldo_id'];
                $dataCoa = Coa_saldo_model_eloquent::with('coa')->where('fin_coa_saldo_id', $fin_coa_saldo_id)->first();
                $fin_coa_id = $dataCoa->coa->fin_coa_id;
                $temp = [
                    'code' => $dataCoa->coa->fin_coa_code,
                    'name' => $dataCoa->coa->fin_coa_name,
                    'data' => [],
                ];

                $dataHistoryCoa = Coa_saldo_history_model_eloquent::where('date_trans', '>=', $date1)->where('date_trans', '<=', $date2)->where('fin_coa_saldo_id', $fin_coa_saldo_id)->orderBy('fin_coa_saldo_history_id', 'ASC')->get()->toArray();
                $tempData = [];
                for ($j = 0; $j < count($dataHistoryCoa); $j++) {

                    $loopContinue = false;

                    $fin_coa_saldo_history_id =  $dataHistoryCoa[$j]['fin_coa_saldo_history_id'];
                    $kunciJurnal = $dataHistoryCoa[$j]['desc'];
                    $no_bukti = '';
                    $id_refer = $dataHistoryCoa[$j]['id_refer'];
                    $table_name = $dataHistoryCoa[$j]['table_name'];
                    $debit = 0;
                    $credit = 0;
                    $saldoAkhir  = $dataHistoryCoa[$j]['become_value'];
                    $custorSupp = '';

                    if (!empty($id_refer)) {
                        if ($table_name == 'fin_gl') {
                            $gl = GL_model_eloquent::find($id_refer);
                            $no_bukti = $gl->fin_gl_no_bukti;
                        }

                        $dataSIA = $this->db->where('fin_coa_id', $fin_coa_id)->where('id_refer', $id_refer)->where('table_name', $table_name)->get('sia')->result();

                        if (count($dataSIA) > 1) {
                            $loopContinue = true;

                            for ($k = 0; $k < count($dataSIA); $k++) {
                                $debit = $dataSIA[$k]->debit;
                                $credit = $dataSIA[$k]->credit;

                                $customer_id = $dataSIA[$k]->customer_id;
                                $supplier_id = $dataSIA[$k]->supplier_id;

                                // if (!empty($customer_id)) {
                                //     $custorSupp = Customers_model_eloquent::find($customer_id)->customer_name;
                                // }

                                // if (!empty($supplier_id)) {
                                //     $custorSupp = Suppliers_model_eloquent::find($supplier_id)->supplier_name;
                                // }

                                $tempData[] = [
                                    'kunciJurnal' => $kunciJurnal,
                                    'no_bukti' => $no_bukti,
                                    'debit' => $debit,
                                    'credit' => $credit,
                                    // 'saldoAkhir' => $saldoAkhir,
                                    'saldoAkhir' => $dataHistoryCoa[$j]['become_value'],
                                    'coa_saldo_history_id' => $fin_coa_saldo_history_id,
                                    'coa_saldo_id' => $dataHistoryCoa[$j]['fin_coa_saldo_id'],
                                    'date_trans' => $dataHistoryCoa[$j]['date_trans'],
                                    'custorSupp' => $custorSupp,
                                ];
                                $j++;
                            }

                            $j--; // back to loop parent
                        } else {
                            $dataSIA = $this->db->where('fin_coa_id', $fin_coa_id)->where('id_refer', $id_refer)->where('table_name', $table_name)->get('sia')->row();
                            $debit = $dataSIA->debit;
                            $credit = $dataSIA->credit;

                            $customer_id = $dataSIA->customer_id;
                            $supplier_id = $dataSIA->supplier_id;


                            // if (!empty($customer_id)) {
                            //     $custorSupp = Customers_model_eloquent::find($customer_id)->customer_name;
                            // }

                            // if (!empty($supplier_id)) {
                            //     $custorSupp = Suppliers_model_eloquent::find($supplier_id)->supplier_name;
                            // }
                        }
                    }

                    if (!$loopContinue) {
                        $tempData[] = [
                            'kunciJurnal' => $kunciJurnal,
                            'no_bukti' => $no_bukti,
                            'debit' => $debit,
                            'credit' => $credit,
                            'saldoAkhir' => $saldoAkhir,
                            'coa_saldo_history_id' => $fin_coa_saldo_history_id,
                            'coa_saldo_id' => $dataHistoryCoa[$j]['fin_coa_saldo_id'],
                            'date_trans' => $dataHistoryCoa[$j]['date_trans'],
                            'custorSupp' => $custorSupp,
                        ];
                    }
                }

                $temp['data'] = $tempData;
                $dataArray[] = $temp;
            }

            // print_r($dataArray);die();

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


        $filename = (str_replace('/', '-', 'BukuBesar_' . $dateFilename)) . '.pdf';
        $fpdf = new Pdf_mc_table('P', 'mm', 'A4');
        $fpdf->SetMargins(10, 10, 10, 10);
        $fpdf->AddPage();

        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->Cell(0, 10, 'Laporan Buku Besar', 0, 1, 'C', 0);
        $fpdf->Cell(0, 10, 'Tanggal : ' . $date1 . ' sd ' . $date2, 0, 1, 'C', 0);

        for ($i = 0; $i < count($dataArray); $i++) {
            $fpdf->SetFont('Arial', 'B', 10);
            $fpdf->Cell(20, 10, $dataArray[$i]['code'], 0, 0, 'l', 0);
            $fpdf->Cell(20, 10, $dataArray[$i]['name'], 0, 1, 'l', 0);

            $border = 1;
            $w_1 = 20;
            $w_2 = 40;
            $w_3 = 25;
            $w_4 = 20;
            $w_5 = 25;
            $w_6 = 25;
            $w_7 = 25;
            $h = 7;
            $fpdf->SetFillColor(255, 255, 255);

            $fpdf->SetWidths(array($w_1, $w_2, $w_3, $w_4, $w_5, $w_6, $w_7));
            $fpdf->SetLineHeight($h);



            $dataSub = $dataArray[$i]['data'];
            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));
            $fpdf->Row(array(
                'Date',
                'Customer/Supplier',
                'Code',
                'No.Bukti',
                'Debit',
                'Credit',
                'Saldo Akhir'
            ));
            $fpdf->SetFont('Arial', '', 8);
            $fpdf->SetAligns(array('L', 'L', 'L', 'L', 'C', 'C', 'C'));
            for ($j = 0; $j < count($dataSub); $j++) {
                $fpdf->Row(array(
                    $dataSub[$j]['date_trans'],
                    $dataSub[$j]['custorSupp'],
                    $dataSub[$j]['kunciJurnal'],
                    $dataSub[$j]['no_bukti'],
                    number($dataSub[$j]['debit']),
                    number($dataSub[$j]['credit']),
                    number(abs($dataSub[$j]['saldoAkhir'])),
                ));
            }
        }


        $fpdf->Output('I', $filename,);
    }
}
