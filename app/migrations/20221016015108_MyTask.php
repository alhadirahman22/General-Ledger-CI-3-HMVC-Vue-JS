<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_MyTask extends CI_Migration
{
    public function up()
    {
        Capsule::schema()->create('mytask', function ($table) {
            $table->bigIncrements('mytask_id');
            $table->bigInteger('id_table');
            $table->string('table', 20);
            $table->string('code', 100)->nullable(true);
            $table->longText('url_direct');
            $table->bigInteger('id_table_refer')->nullable(true);
            $table->string('table_refer', 20)->nullable(true);
            $table->string('code_refer', 100)->nullable(true);
            $table->longText('desc')->nullable(true);
            $table->longText('url_refer')->nullable(true);
            $table->enum('status', ['0', '1'])->comment('0 = Not action 1 = done')->default('0');
            $table->bigInteger('employee_id');
            $table->timestamps();
        });

        Capsule::schema()->create('mytask_setting', function ($table) {
            $table->bigIncrements('mytask_setting_id');
            $table->string('name', 100)->nullable(true);
            $table->string('code_task', 100)->nullable(true);
            $table->longText('template')->nullable(true);
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists('mytask');
        Capsule::schema()->dropIfExists('mytask_setting');
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
