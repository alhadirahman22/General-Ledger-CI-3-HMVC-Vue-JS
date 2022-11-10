
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_InsertSetting20221110224451 extends CI_Migration
{

    protected $data_insert = [
        [
            'key' => '_coa_kas_default',
            'value' => 1,
        ],
    ];

    public function up()
    {
        $this->db->insert_batch('settings', $this->data_insert);
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        $data_insert = $this->data_insert;
        for ($i = 0; $i < count($data_insert); $i++) {
            $key = $data_insert[$i]['key'];
            $this->db->where('key', $key)->delete('settings');
        }

        Capsule::schema()->enableForeignKeyConstraints();
    }
}
