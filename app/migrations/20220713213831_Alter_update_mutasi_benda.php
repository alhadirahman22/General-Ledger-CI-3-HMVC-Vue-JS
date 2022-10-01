
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_Alter_update_mutasi_benda extends CI_Migration
{

    public function up()
    {
        Capsule::schema()->dropIfExists('mutasi_parent');
        Capsule::schema()->table('mutasi_benda', function ($table) {
            $table->bigInteger('benda_id');
            $table->bigInteger('jenis_mutasi_id');
            $table->dropColumn('mutasi_parent_id');
            $table->softDeletes();
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->create('mutasi_parent', function ($table) {
            $table->bigIncrements('mutasi_parent_id');
            $table->bigInteger('benda_id');
            $table->bigInteger('jenis_mutasi_id');
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Capsule::schema()->table('mutasi_benda', function ($table) {
            $table->dropColumn('benda_id');
            $table->dropColumn('jenis_mutasi_id');
            $table->bigInteger('mutasi_parent_id');
            $table->softDeletes();
        });

        Capsule::schema()->enableForeignKeyConstraints();
    }
}
