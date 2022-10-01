<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_MutasiMenu extends CI_Migration
{

    protected $data_menu = [
        [
            'name' => 'mutasi',
            'definition' => 'Mutasi',
        ],

        [
            'name' => 'mutasi/mutasi_benda',
            'definition' => 'Mutasi - Mutasi Benda',
        ],

        [
            'name' => 'mutasi/mutasi_benda/add',
            'definition' => 'Mutasi - Mutasi Benda - Add',
        ],

        [
            'name' => 'mutasi/mutasi_benda/view',
            'definition' => 'Mutasi - Mutasi Benda - View Details',
        ],

        [
            'name' => 'mutasi/mutasi_benda/delete',
            'definition' => 'Mutasi - Mutasi Benda - Delete',
        ],

        [
            'name' => 'mutasi/jenis_mutasi',
            'definition' => 'Mutasi - Jenis Mutasi',
        ],

        [
            'name' => 'mutasi/jenis_mutasi/add',
            'definition' => 'Mutasi - Jenis Mutasi - Add',
        ],

        [
            'name' => 'mutasi/jenis_mutasi/edit',
            'definition' => 'Mutasi - Jenis Mutasi - Edit',
        ],

        [
            'name' => 'mutasi/jenis_mutasi/delete',
            'definition' => 'Mutasi - Jenis Mutasi - delete',
        ],


        [
            'name' => 'mutasi/management_persetujuan',
            'definition' => 'Mutasi - Management Persetujuan',
        ],

        [
            'name' => 'mutasi/management_persetujuan/add',
            'definition' => 'Mutasi - Management Persetujuan - Add',
        ],

        [
            'name' => 'mutasi/management_persetujuan/delete',
            'definition' => 'Mutasi - Management Persetujuan - Delete',
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
