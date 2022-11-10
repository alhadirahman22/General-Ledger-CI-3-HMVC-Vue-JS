


<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_Menu20221110202835 extends CI_Migration
{

    protected $data_menu = [
        [
            'name' => 'reimbursment/pay',
            'definition' => 'Reimbursment - Pay',
        ],

    ];

    public function up()
    {
        $this->db->insert_batch('aauth_perms', $this->data_menu);
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        $data_menu = $this->data_menu;
        for ($i = 0; $i < count($data_menu); $i++) {
            $name = $data_menu[$i]['name'];
            $this->db->where('name', $name)->delete('aauth_perms');
        }

        Capsule::schema()->enableForeignKeyConstraints();
    }
}
