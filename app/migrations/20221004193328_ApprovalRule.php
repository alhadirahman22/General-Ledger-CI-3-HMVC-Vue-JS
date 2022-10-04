<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_ApprovalRule extends CI_Migration
{
    public function up()
    {
        Capsule::schema()->create('approval_rule', function ($table) {
            $table->bigIncrements('approval_rule_id');
            $table->string('name', 100);
            $table->enum('type_approval', ['1', '2'])->comment('1 = series, 2 = paralel ')->default('1');
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Capsule::schema()->create('approval_rule_department', function ($table) {
            $table->bigIncrements('approval_rule_department_id');
            $table->bigInteger('approval_rule_id');
            $table->bigInteger('department_id');
            $table->enum('type_approval', ['1', '2'])->comment('1 = series, 2 = paralel ')->default('1');
        });

        Capsule::schema()->create('approval_rule_department_emp', function ($table) {
            $table->bigIncrements('approval_rule_department_emp_id');
            $table->bigInteger('approval_rule_department_id');
            $table->bigInteger('jabatan_id')->nullable(true)->default(null);
            $table->bigInteger('employee_id')->nullable(true)->default(null);
        });

        Capsule::schema()->create('approval_rule_config', function ($table) {
            $table->bigIncrements('approval_rule_config_id');
            $table->bigInteger('approval_rule_id');
            $table->string('code_approval', 100);
            $table->string('name', 100);
            $table->string('main_table', 100)->nullable(true)->default(null);
            $table->text('desc')->nullable(true);
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists('approval_rule');
        Capsule::schema()->dropIfExists('approval_rule_department');
        Capsule::schema()->dropIfExists('approval_rule_department_emp');
        Capsule::schema()->dropIfExists('approval_rule_config');
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
