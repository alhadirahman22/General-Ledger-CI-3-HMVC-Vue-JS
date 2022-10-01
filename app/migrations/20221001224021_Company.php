
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_Company extends CI_Migration
{
    public function up()
    {
        Capsule::schema()->create('company', function ($table) {
            $table->bigIncrements('company_id');
            $table->string('name', 100);
            $table->string('logo', 255)->nullable(true);
            $table->string('abbrname', 15)->nullable(true);
            $table->text('address1', 15)->nullable(true);
            $table->text('address2', 15)->nullable(true);
            $table->text('address3', 15)->nullable(true);
            $table->text('file_perusahaan', 255)->nullable(true);
        });

        Capsule::schema()->create('company_bank', function ($table) {
            $table->bigIncrements('company_bank_id');
            $table->bigInteger('company_id');
            $table->bigInteger('bank_id');
            $table->string('account_number', 150);
            $table->text('address', 20);
            $table->string('account_name', 100);
            $table->string('swift_code', 100)->nullable(true);
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists('company');
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
