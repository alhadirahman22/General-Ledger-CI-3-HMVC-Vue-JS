<?php
// include_once APPPATH . 'vendor/autoload.php'; // move to framework.php
use Illuminate\Database\Capsule\Manager as Capsule;

class MigrationData extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->library('migration');

        if ($this->migration->current() === FALSE) {
            show_error($this->migration->error_string());
        }
    }

    public function reset()
    {
        $this->load->library('migration');
        $this->migration->version(20220628121516);
        $this->index();
    }

    public function importSql()
    {
        $db = $this->db;
        // $hostname = $db->hostname;
        $username = $db->username;
        $password = $db->password;
        $database = $db->database;
        $pathFile = FCPATH . 'sql\museum.sql';
        $env_app = getenv('APP_ENV');
        if ($env_app == 'local') {
            if (empty($password)) {
                $command = "mysql -u " . $username . " " . $database  . " < " . $pathFile . "";
            } else {
                $command = "mysql -u " . $username . " -p" . $password . " " . $database . " < " . $pathFile . "";
            }


            $output = shell_exec($command . " 2>&1");
            if (empty($output)) {
                print_r('Successfull');
            } else {
                echo "<pre>$output</pre>";
            }
        }
    }
}
