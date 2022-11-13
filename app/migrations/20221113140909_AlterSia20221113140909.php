
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_AlterSia20221113140909 extends CI_Migration
{

    public function up()
    {
        Capsule::schema()->table('sia', function ($table) {
            $table->date('date_trans')->nullable(true)->default(null);
            $table->bigInteger('id_refer_sub_1')->nullable(true)->default(null);
            $table->string('table_name_sub_1', 100)->nullable(true)->default(null);
            $table->bigInteger('id_refer_sub_2')->nullable(true)->default(null);
            $table->string('table_name_sub_2', 100)->nullable(true)->default(null);
        });

        Capsule::schema()->table('fin_coa_saldo_history', function ($table) {
            $table->bigInteger('id_refer_sub_1')->nullable(true)->default(null);
            $table->string('table_name_sub_1', 100)->nullable(true)->default(null);
            $table->bigInteger('id_refer_sub_2')->nullable(true)->default(null);
            $table->string('table_name_sub_2', 100)->nullable(true)->default(null);
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
