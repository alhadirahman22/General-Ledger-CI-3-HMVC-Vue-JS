
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_StatusGL extends CI_Migration
{

    public function up()
    {
        Capsule::schema()->table('fin_gl', function ($table) {
            $table->enum('status', ['0', '1'])->comment('1 = Issued, 0 = Draft')->default('0');
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
