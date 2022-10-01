
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_MutasiApproval extends CI_Migration
{
    public function up()
    {
        Capsule::schema()->table('klasifikasi', function ($table) {
            $table->enum('type_approval', ['1', '2'])->comment('1 = series, 2 = paralel ')->default('1');
            $table->string('prefix_id', 200)->nullable(true);
        });

        Capsule::schema()->create('klasifikasi_department', function ($table) {
            $table->bigIncrements('klasifikasi_department_id');
            $table->bigInteger('klasifikasi_id');
            $table->bigInteger('department_id');
            $table->enum('type_approval', ['1', '2'])->comment('1 = series, 2 = paralel ')->default('1');
        });

        Capsule::schema()->create('klasifikasi_department_approval', function ($table) {
            $table->bigIncrements('klasifikasi_department_approval_id');
            $table->bigInteger('klasifikasi_department_id');
            $table->bigInteger('jabatan_id');
        });

        Capsule::schema()->create('mutasi_benda', function ($table) {
            $table->bigIncrements('mutasi_benda_id');
            $table->bigInteger('benda_id');
            $table->bigInteger('requested_by')->comment('employee');
            $table->enum('status', ['1', '-1', '0', '2'])->comment('1 = approved, 0 = not action, -1 = reject, 2 =  progress ')->default('0');
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->timestamps();
        });

        Capsule::schema()->create('mutasi_benda_department_approval', function ($table) {
            $table->bigIncrements('mutasi_benda_department_approval_id');
            $table->bigInteger('mutasi_benda_id');
            $table->bigInteger('department_id');
            $table->enum('status', ['1', '-1', '0', '2', '-2'])->comment('1 = approved, 0 = not action, -1 = reject, 2 =  progress, -2 = awaiting ')->default('0');
            $table->enum('condition', ['1', '0', '2'])->comment('1 = now, 0 = unknown, 2 = done');
        });


        Capsule::schema()->create('mutasi_benda_approval', function ($table) {
            $table->bigIncrements('mutasi_benda_approval_id');
            $table->bigInteger('mutasi_benda_department_approval_id');
            $table->bigInteger('employee_id');
            $table->enum('type_approval', ['1', '2'])->comment('1 = series, 2 = paralel ')->default('1');
            $table->enum('status', ['1', '-1', '0', '2', '-2'])->comment('1 = approved, 0 = not action, -1 = reject, 2 =  progress, -2 = awaiting ')->default('0');
            $table->enum('condition', ['1', '0', '2'])->comment('1 = now, 0 = unknown, 2 = done');
        });

        // when step is done then insert this one as awaiting to next step
        Capsule::schema()->create('mutasi_benda_approval_log', function ($table) {
            $table->bigIncrements('mutasi_benda_approval_log_id');
            $table->bigInteger('parent_mutasi_benda_approval_log_id')->nullable(true);
            $table->bigInteger('mutasi_benda_approval_id');
            $table->text('desc')->nullable(true);
            $table->enum('status', ['1', '-1', '0', '2', '-2'])->comment('1 = approved, 0 = not action, -1 = reject, 2 =  progress, -2 = awaiting ')->default('0');
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists('klasifikasi_department');
        Capsule::schema()->dropIfExists('klasifikasi_department_approval');
        Capsule::schema()->dropIfExists('mutasi_benda');
        Capsule::schema()->dropIfExists('mutasi_benda_department_approval');
        Capsule::schema()->dropIfExists('mutasi_benda_approval');
        Capsule::schema()->dropIfExists('mutasi_benda_approval_log');

        Capsule::schema()->table('klasifikasi', function ($table) {
            $table->dropColumn('type_approval');
            $table->dropColumn('prefix_id');
        });
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
