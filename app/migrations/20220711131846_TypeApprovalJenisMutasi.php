
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_TypeApprovalJenisMutasi extends CI_Migration
{
    public function up()
    {
        // delete type approval in klasifikasi id
        Capsule::schema()->table('klasifikasi', function ($table) {
            $table->dropColumn('type_approval');
        });

        Capsule::schema()->table('jenis_mutasi', function ($table) {
            $table->enum('type_approval', ['1', '2'])->comment('1 = series, 2 = paralel ')->default('1');
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->table('klasifikasi', function ($table) {
            $table->enum('type_approval', ['1', '2'])->comment('1 = series, 2 = paralel ')->default('1');
        });
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
