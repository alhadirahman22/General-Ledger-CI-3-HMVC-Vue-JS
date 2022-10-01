
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_Alter_museum_jenis_mutasi extends CI_Migration
{

    public function up()
    {
        Capsule::schema()->table('jenis_mutasi', function ($table) {
            $table->bigInteger('museum_id')->default(1);
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->table('jenis_mutasi', function ($table) {
            $table->dropColumn('museum_id');
        });
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
