
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_AlterSia extends CI_Migration
{

    public function up()
    {
        Capsule::schema()->table('sia', function ($table) {
            $table->bigInteger('customer_id')->nullable(true);
            $table->bigInteger('supplier_id')->nullable(true);
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
