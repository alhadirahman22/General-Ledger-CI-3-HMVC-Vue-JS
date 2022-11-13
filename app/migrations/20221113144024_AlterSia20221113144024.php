
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_AlterSia20221113144024 extends CI_Migration
{

    public function up()
    {
        Capsule::schema()->table('fin_coa_saldo_history', function ($table) {
            $table->bigInteger('sia_id')->nullable(true)->default(null);
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
