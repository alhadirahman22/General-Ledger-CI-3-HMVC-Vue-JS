<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_LevelJabatan extends CI_Migration
{
    public function up()
    {

        Capsule::schema()->create('level_jabatan', function ($table) {
            $table->bigIncrements('level_jabatan_id');
            $table->string('name', 100);
            $table->integer('value');
        });

        $data_insert = [
            [
                'name' => 'Direktur Utama',
                'value' => 1,
            ],
            [
                'name' => 'Komisaris',
                'value' => 1,
            ],
            [
                'name' => 'Direktur',
                'value' => 2,
            ],
            [
                'name' => 'General Manager',
                'value' => 3,
            ],
            [
                'name' => 'Manager',
                'value' => 4,
            ],
            [
                'name' => 'Supervisor',
                'value' => 5,
            ],
            [
                'name' => 'Senior Staff',
                'value' => 6,
            ],
            [
                'name' => 'Junior Staff',
                'value' => 7,
            ],
            [
                'name' => 'Honor Staff',
                'value' => 8,
            ],

        ];
        $this->db->insert_batch('level_jabatan', $data_insert);
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists('level_jabatan');
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
