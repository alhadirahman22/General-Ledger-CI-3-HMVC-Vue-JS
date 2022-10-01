
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_AlterJabatan extends CI_Migration
{

    public function up()
    {
        Capsule::schema()->table('jabatan', function ($table) {
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
