
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_AlterJabatanDepartment extends CI_Migration
{

    public function up()
    {
        Capsule::schema()->table('jabatan', function ($table) {
            $table->bigInteger('department_id')->default(1);
            $table->bigInteger('company_id')->default(1);
        });

        Capsule::schema()->table('jabatan_department_employee', function ($table) {
            $table->bigInteger('company_id')->default(1);
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
