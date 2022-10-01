<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

class Template
{

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function _init($template = 'default')
    {
        $this->ci->output->set_template($template);

        // load modal html
        $MODAL = $this->ci->load->view('modal', '', true);
        $this->ci->output->set_output_data('MODAL', $MODAL);

        $this->ci->load->css('assets/css/bootstrap.min.css');
        $this->ci->load->css('assets/font-awesome/4.5.0/css/font-awesome.min.css');
        $this->ci->load->css('assets/font-awesome/fontawesome/font-awesome.min.css');
        $this->ci->load->css('assets/font-awesome/fontawesome4/css/font-awesome.min.css');
        $this->ci->load->css('assets/font-awesome/font-awesome-animation.min.css');
        // $this->ci->load->css('assets/font-awesome/fontawesome5/css/all.min.css');
        $this->ci->load->css('assets/css/fonts.googleapis.com.css');
        $this->ci->load->css('assets/css/ace.min.css');
        $this->ci->load->css('assets/css/ace-rtl.min.css');
        $this->ci->load->css('assets/jquery-ui/jquery-ui.min.css');
        $this->ci->load->css('assets/toastr/toastr.min.css');

        $this->ci->load->css('assets/select2/css/select2.css');
        $this->ci->load->css('assets/select2/select2-bootstrap.min.css');
        $this->ci->load->css('assets/css/bootstrap-datetimepicker.min.css');
        $this->ci->load->css('assets/datepicter/css/bootstrap-datetimepicker.min.css');

        $this->ci->load->css('assets/css/daterangepicker.min.css');
        $this->ci->load->css('assets/css/custom.css');

        $this->ci->load->js('assets/jwt/encode/hmac-sha256.js');
        $this->ci->load->js('assets/jwt/encode/enc-base64-min.js');
        $this->ci->load->js('assets/jwt/encode/jwt.encode.js');
        $this->ci->load->js('assets/jwt/decode/build/jwt-decode.min.js');

        $this->ci->load->js('assets/js/moment.min.js');
        $this->ci->load->js('assets/js/moment-with-locales.min.js');
        $this->ci->load->js('assets/js/app_template.js');

        // $this->ci->load->js('public/js/app.js'); // vue CLI




        // image fitter
        //$this->ci->load->js('assets/img-fitter/jquery.imgFitter.js');

        $this->ci->load->js('public/js/app.js'); // vue CLI



        $this->ci->load->js('assets/js/jquery-2.1.4.min.js');
        $this->ci->load->js('assets/js/jquery-2.2.0.min.js');
        $this->ci->load->js('assets/js/bootstrap.min.js');
        $this->ci->load->js('assets/jquery-ui/jquery-ui.min.js');

        $this->ci->load->js('assets/js/client_rest.js');


        if ($template == 'default') {
            $this->ci->load->js('assets/js/ace-extra.min.js');
            $this->ci->load->js('assets/js/ace-elements.min.js');
            $this->ci->load->js('assets/js/ace.min.js');

            $PARAM_DATA = $this->load_template_default();
            foreach ($PARAM_DATA as $key => $value) {
                $this->ci->output->set_output_data($key, $value);
            }
        }





        $this->ci->load->js('assets/select2/js/select2.js');
        $this->ci->load->js('assets/toastr/toastr.min.js');


        // $this->ci->load->css('assets/datatables/dataTables.bootstrap4.min.css');

        $this->ci->load->css('assets/summernote/summernote.css');
        //$this->ci->load->js('assets/summernote/summernote.js');

        $this->ci->load->css('assets/jquery-confirmv3/jquery-confirm.min.css');
        $this->ci->load->js('assets/jquery-confirmv3/jquery-confirm.min.js');


        // $this->ci->load->js('assets/datatables/jquery.dataTables.min.js');
        // $this->ci->load->js('assets/datatables/dataTables.bootstrap4.min.js');

        // $this->ci->load->js('assets/jquerytable2excel/jquery.table2excel.js');

        //$this->ci->load->js('assets/js/jquery.maskMoney.js');
        $this->ci->load->js('assets/js/jquery.maskedinput.min.js');
        $this->ci->load->js('assets/datepicter/js/bootstrap-datetimepicker.min.js');

        $this->ci->load->js('assets/js/daterangepicker.min.js');
        $this->ci->load->js('assets/js/jquery.mask.js');

        $this->ci->load->js('assets/summernote/summernote.js');
        // image fitter
        $this->ci->load->js('assets/img-fitter/jquery.imgFitter.js');
        $this->ci->load->js('assets/js/jquery.maskMoney.js');

        $this->ci->load->css('assets/orgchart/orgchart.css');
        $this->ci->load->js('assets/orgchart/orgchart.js');

        $this->ci->load->css('assets/css/dropzone.min.css');
        $this->ci->load->js('assets/js/dropzone.min.js');

        $this->ci->load->js('assets/js/jquery.easypiechart.min.js');
        $this->ci->load->js('assets/js/jquery.sparkline.index.min.js');
        $this->ci->load->js('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.2/chart.min.js');

        // vue

        // $this->ci->load->js('assets/js/vue/vue.js');
        // $this->ci->load->js('assets/js/vue/vue-router.js');
        // $this->ci->load->js('https://unpkg.com/vue-runtime-template-compiler/dist/vue-runtime-template-compiler.umd.js');
    }

    private function load_template_default()
    {
        $segments = [];
        // $uri_string = $this->ci->uri->uri_string();
        $uri_string = '';

        for ($i = 1; $i <= 5; $i++) {
            $data_segment = $this->ci->uri->segment($i);
            if ($data_segment != '') {

                // $data_segment_to_uri = () ? '' : $data_segment;
                $data_segment_to_uri = (strlen($data_segment) <= 70 && !is_numeric($data_segment))
                    ? $data_segment : '';

                if ($data_segment_to_uri != '') {
                    $data_segment_to_uri = str_replace('form', 'add', $data_segment_to_uri);
                    $bcs = ($uri_string == '') ? '' : '/';
                    $uri_string = $uri_string . $bcs . $data_segment_to_uri;
                }


                $data_segment = str_replace("-", " ", $data_segment);
                array_push($segments, ucwords($data_segment));
            }
        }

        $data['segments'] = $segments;
        $data['uri_string'] = $uri_string;

        // print_r($data);
        // die();

        $data_help_uri = $this->ci->db->select('help_uri')->get_where('aauth_perms', array('name' => $uri_string))->row();
        $data['help_uri'] = ($data_help_uri) ? $data_help_uri->help_uri : '';

        $data['include'] = ''; //$this->load->view('template/include',$data,true);
        $data['activemenu'] = $this->ci->uri->segment(1);
        $data['navbar'] = $this->ci->load->view('template/navbar', $data, true);
        $data['sidebar'] = $this->ci->load->view('template/sidebar', $data, true);
        $data['breadcrumbs'] = $this->ci->load->view('template/breadcrumbs', $data, true);
        return $data;
    }

    public function table()
    {
        // $this->ci->load->js('assets/js/tables/datatables/datatables.min.js');
        // $this->ci->load->js('assets/js/tables/table.js');

        $this->ci->load->js('assets/js/jquery.dataTables.min.js');
        $this->ci->load->js('assets/js/jquery.dataTables.bootstrap.min.js');
        $this->ci->load->js('assets/js/tables/table.js');
    }

    public function form($load_form = true)
    {
        $this->ci->load->css('assets/css/datepicker.min.css');
        $this->ci->load->css('assets/js/forms/bootstrap-fileinput/bootstrap-fileinput.css');
        $this->ci->load->css('assets/css/bootstrap-duallistbox.min.css');



        $this->ci->load->js('assets/js/forms/uniform.min.js');
        $this->ci->load->js('assets/js/forms/jquery.number.min.js');
        $this->ci->load->js('assets/js/forms/select2.min.js');
        $this->ci->load->js('assets/js/forms/tinymce/tinymce.min.js');
        $this->ci->load->js('assets/js/forms/validation/validate.min.js');
        $this->ci->load->js('assets/js/forms/jquery.form.min.js');
        $this->ci->load->js('assets/js/forms/datepicker.min.js');
        $this->ci->load->js('assets/js/forms/bootstrap-fileinput/bootstrap-fileinput.js');
        $this->ci->load->js('assets/js/forms/bootstrap-multiselect.js');
        $this->ci->load->js('assets/js/forms/bootstrap-tagsinput.min.js');
        $this->ci->load->js('assets/js/forms/switch.min.js');
        $this->ci->load->js('assets/js/forms/xlsx.full.min.js');
        // <script src="assets/js/jquery.bootstrap-duallistbox.min.js"></script>
        $this->ci->load->js('assets/js/jquery.bootstrap-duallistbox.min.js');
        $this->ci->load->js('assets/js/bootstrap-tag.min.js');
        // <script src="assets/js/bootstrap-tag.min.js"></script>
        $this->ci->load->js('assets/js/typeahead.bundle.min.js');

        // <script src="assets/js/dropzone.min.js"></script>
        // $this->ci->load->js('https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.10.3/typeahead.bundle.min.js');
        if ($load_form) {
            $this->ci->load->js('assets/js/forms/form.js');
        }
    }
}
