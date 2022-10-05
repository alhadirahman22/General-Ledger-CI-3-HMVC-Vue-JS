
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_StrukturJabatanDepartment extends CI_Migration
{
    public function up()
    {
        Capsule::schema()->create('jabatan', function ($table) {
            $table->bigIncrements('jabatan_id');
            $table->string('name', 100);
            $table->bigInteger('level_jabatan_id');
        });

        Capsule::schema()->create('jabatan_department_employee', function ($table) {
            $table->bigIncrements('jabatan_department_id');
            $table->bigInteger('department_id');
            $table->bigInteger('jabatan_id');
            $table->bigInteger('employee_id');
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists('jabatan');
        Capsule::schema()->dropIfExists('jabatan_department_employee');
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
