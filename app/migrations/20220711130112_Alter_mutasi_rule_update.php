
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_Alter_mutasi_rule_update extends CI_Migration
{
    public function up()
    {
        Capsule::schema()->rename('klasifikasi_department', 'jenis_mutasi_department');

        Capsule::schema()->table('jenis_mutasi_department', function ($table) {
            $table->renameColumn('klasifikasi_department_id', 'jenis_mutasi_department_id');
            $table->renameColumn('klasifikasi_id', 'jenis_mutasi_id');
        });

        Capsule::schema()->rename('klasifikasi_department_approval', 'jenis_mutasi_department_approval');
        Capsule::schema()->table('jenis_mutasi_department_approval', function ($table) {
            $table->renameColumn('klasifikasi_department_approval_id', 'jenis_mutasi_department_approval_id');
            $table->renameColumn('klasifikasi_department_id', 'jenis_mutasi_department_id');
        });

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
            $table->renameColumn('benda_id', 'mutasi_parent_id');
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->rename('jenis_mutasi_department', 'klasifikasi_department');
        Capsule::schema()->table('klasifikasi_department', function ($table) {
            $table->renameColumn('jenis_mutasi_department_id', 'klasifikasi_department_id');
            $table->renameColumn('jenis_mutasi_id', 'klasifikasi_id');
        });

        Capsule::schema()->rename('jenis_mutasi_department_approval', 'klasifikasi_department_approval');
        Capsule::schema()->table('klasifikasi_department_approval', function ($table) {
            $table->renameColumn('jenis_mutasi_department_approval_id', 'klasifikasi_department_approval_id');
            $table->renameColumn('jenis_mutasi_department_id', 'klasifikasi_department_id');
        });

        Capsule::schema()->dropIfExists('mutasi_parent');
        Capsule::schema()->table('mutasi_benda', function ($table) {
            $table->renameColumn('mutasi_parent_id', 'benda_id');
        });

        Capsule::schema()->enableForeignKeyConstraints();
    }
}
