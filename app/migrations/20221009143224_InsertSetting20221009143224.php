
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_InsertSetting20221009143224 extends CI_Migration
{
    public function up()
    {
        $data_insert = [
            [
                'key' => 'prefix_reimbursment',
                'value' => '#RMB',
            ],
            [
                'key' => 'code_approval_reimbursment',
                'value' => generate_random_letters(5),
            ],
        ];
        $this->db->insert_batch('settings', $data_insert);
    }

    public function down()
    {
        $this->db->where('key', 'prefix_reimbursment');
        $this->db->delete('settings');

        $this->db->where('key', 'code_approval_reimbursment');
        $this->db->delete('settings');
    }
}
