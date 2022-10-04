<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_Reimbursment extends CI_Migration
{
    public function up()
    {
        Capsule::schema()->create('reimbursment', function ($table) {
            $table->bigIncrements('reimbursment_id');
            $table->string('code', 100);
            $table->string('name', 100);
            $table->date('date_reimbursment');
            $table->text('desc')->nullable(true);
            $table->text('link_file')->nullable(true);
            $table->text('pathfile')->nullable(true);
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Capsule::schema()->create('reimbursment_dept_approval', function ($table) {
            $table->bigIncrements('reimbursment_dept_approval_id');
            $table->bigInteger('reimbursment_id');
            $table->bigInteger('department_id');
            $table->enum('status', ['1', '-1', '0', '2', '-2'])->comment('1 = approved, 0 = not action, -1 = reject, 2 =  progress, -2 = awaiting ')->default('0');
            $table->enum('condition', ['1', '0', '2'])->comment('1 = now, 0 = unknown, 2 = done');
        });

        Capsule::schema()->create('reimbursment_dept_approval_emp', function ($table) {
            $table->bigIncrements('reimbursment_dept_approval_emp_id');
            $table->bigInteger('reimbursment_dept_approval_id');
            $table->bigInteger('employee_id');
            $table->enum('type_approval', ['1', '2'])->comment('1 = series, 2 = paralel ')->default('1');
            $table->enum('status', ['1', '-1', '0', '2', '-2'])->comment('1 = approved, 0 = not action, -1 = reject, 2 =  progress, -2 = awaiting ')->default('0');
            $table->enum('condition', ['1', '0', '2'])->comment('1 = now, 0 = unknown, 2 = done');
        });

        // when step is done then insert this one as awaiting to next step
        Capsule::schema()->create('reimbursment_approval_log', function ($table) {
            $table->bigIncrements('reimbursment_approval_log_id');
            $table->bigInteger('parent_reimbursment_approval_log')->nullable(true);
            $table->bigInteger('reimbursment_dept_approval_emp_id');
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
        Capsule::schema()->dropIfExists('reimbursment');
        Capsule::schema()->dropIfExists('reimbursment_dept_approval');
        Capsule::schema()->dropIfExists('reimbursment_dept_approval_emp');
        Capsule::schema()->dropIfExists('reimbursment_approval_log');
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
