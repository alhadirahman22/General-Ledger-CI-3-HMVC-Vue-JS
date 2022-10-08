
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_AlterReimbursment extends CI_Migration
{

    public function up()
    {
        Capsule::schema()->table('reimbursment', function ($table) {
            $table->bigInteger('requested_by')->comment('employee');
            $table->enum('status', ['1', '-1', '0', '2', '99'])->comment('1 = approved, 0 = not action, -1 = reject, 2 =  progress , 99 = Terbayarkan ')->default('0');
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
