<?php
// include_once APPPATH . 'vendor/autoload.php'; // move to framework.php

class Generate extends CI_Controller
{

    private $_client;

    public function __construct()
    {
        parent::__construct();

        $this->_client = new \GuzzleHttp\Client();

        // print_r('No data');
        // die();
    }

    public function index()
    {
        $data['module'] = $this->getDirContents(FCPATH . 'modules');

        $this->load->view('template/generate_page', $data);
    }

    public function create_module()
    {
        $name = $this->input->post('name');

        $path = FCPATH . "modules/" . $name;

        if (!is_dir($path)) {
            mkdir($path);
            mkdir($path . '/controllers');
            mkdir($path . '/language');
            mkdir($path . '/language/english');
            mkdir($path . '/language/indonesia');
            mkdir($path . '/models');
            mkdir($path . '/views');

            $this->session->set_flashdata('feedback', 'Successfully Create.');
        } else {
            $this->session->set_flashdata('feedback', 'Module already exist!');
        }

        return redirect(base_url('migrate'));
    }

    public function create_page()
    {
        $path = $this->input->post('path');
        $name = $this->input->post('name');
        $controller = $this->input->post('controller');
        $language = $this->input->post('language');
        $model = $this->input->post('model');
        $view = $this->input->post('view');

        $name_file = ucwords(strtolower($name));
        if ($controller == 1) {
            // echo "Creating Controller";
            $res_c = $this->createController($name_file, $path);
            $this->session->set_flashdata('feedback_c', ($res_c)
                ? 'Controller Successfully Create.' : 'Controller Already Exist!');
        }

        if ($language == 1) {
            // echo "Creating Language";
            $res_l = $this->createLanguage($name_file, $path);
            $this->session->set_flashdata('feedback_l', ($res_l)
                ? 'Language Successfully Create.' : 'Language Already Exist!');
        }

        if ($model == 1) {
            // echo "Creating Model";
            $res_m = $this->createModel($name_file, $path);
            $this->session->set_flashdata('feedback_m', ($res_m)
                ? 'Model Successfully Create.' : 'Model Already Exist!');
        }

        if ($view == 1) {
            // echo "Creating view";
            $res_v = $this->createView($name_file, $path);
            $this->session->set_flashdata('feedback_v', ($res_v)
                ? 'View Successfully Create.' : 'View Already Exist!');
        }

        return redirect(base_url('migrate'));
    }

    function getDirContents($dir, &$results = array())
    {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                // $results[] = $path;
            } else if ($value != "." && $value != "..") {
                // $this->getDirContents($path, $results);
                $results[] = $path;
            }
        }

        return $results;
    }

    public function createController($name_file, $path)
    {

        $path_new_file = $path . "/controllers/" . $name_file . '.php';
        $result = false;
        if (!file_exists($path_new_file)) {
            $content = '';

            if ($stream = fopen(FCPATH . 'app/views/basic/controller_page.txt', 'r')) {
                // print all the page starting at the offset 10
                $content = stream_get_contents($stream, -1, 1);
                fclose($stream);
            }

            $myfile = fopen($path_new_file, "w") or die("Unable to open file!");
            $txt = '<' . $content;
            fwrite($myfile, $txt);
            fclose($myfile);
            $result = true;
        }

        return $result;
    }

    public function createLanguage($name_file, $path)
    {

        $name_file = strtolower($name_file);
        $path_new_file = $path . "/language/english/" . $name_file . '_lang.php';
        $result = false;

        $txt = '<?php $lang[\'heading\'] = \'heading\';';

        if (!file_exists($path_new_file)) {
            $myfile = fopen($path_new_file, "w") or die("Unable to open file!");
            fwrite($myfile, $txt);
            fclose($myfile);

            $myfile_1 = fopen($path . "/language/indonesia/" . $name_file . '_lang.php', "w") or die("Unable to open file!");
            fwrite($myfile_1, $txt);
            fclose($myfile_1);
            $result = true;
        }

        return $result;
    }

    public function createModel($name_file, $path)
    {

        $path_new_file = $path . "/models/" . $name_file . '_model.php';
        $result = false;

        if (!file_exists($path_new_file)) {
            $content = '';

            if ($stream = fopen(FCPATH . 'app/views/basic/model_page.txt', 'r')) {
                // print all the page starting at the offset 10
                $content = stream_get_contents($stream, -1, 1);
                fclose($stream);
            }

            $myfile = fopen($path_new_file, "w") or die("Unable to open file!");
            $txt = '<' . $content;
            fwrite($myfile, $txt);
            fclose($myfile);
            $result = true;
        }

        return $result;
    }

    public function createView($name_file, $path)
    {

        $name_file = strtolower($name_file);

        $path_new_file = $path . "/views/" . $name_file . '.php';
        $result = false;

        if (!file_exists($path_new_file)) {
            $myfile = fopen($path_new_file, "w") or die("Unable to open file!");
            fclose($myfile);
            $result = true;
        }

        return $result;
    }

    public function index2()
    {
        $this->load->library('migration');

        if ($this->migration->current() === FALSE)
        // if ($this->migration->latest() === FALSE)
        // if ($this->migration->version(20210722100535) === FALSE)
        {
            show_error($this->migration->error_string());
        }
    }
}
