
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_Settings20221103122125 extends CI_Migration
{

    public function up()
    {

        $data_insert = [
            [
                'key' => 'company_id',
                'value' => '1',
            ],
        ];
        $this->db->insert_batch('settings', $data_insert);
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        $this->db->where('key', 'company_id');
        $this->db->delete('settings');
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
