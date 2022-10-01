
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_AlterMutasiBenda extends CI_Migration
{

    public function up()
    {
        Capsule::schema()->table('mutasi_benda', function ($table) {
            $table->text('reason')->nullable(true);
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
