
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_SqlFirst extends CI_Migration
{
    public function up()
    {
        //redirect(base_url() . 'migrationdata/importSql');
    }

    public function down()
    {
    }
}
