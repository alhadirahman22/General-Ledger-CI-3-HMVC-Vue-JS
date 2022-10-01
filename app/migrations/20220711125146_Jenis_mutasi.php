
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_Jenis_mutasi extends CI_Migration
{
    public function up()
    {
        Capsule::schema()->create('jenis_mutasi', function ($table) {
            $table->bigIncrements('jenis_mutasi_id');
            $table->string('name', 100);
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists('jenis_mutasi');
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
