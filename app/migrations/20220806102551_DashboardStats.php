
<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_DashboardStats extends CI_Migration
{

    public function up()
    {
        Capsule::schema()->create('dashboard_stats', function ($table) {
            $table->bigIncrements('dashboard_stats_id');
            $table->integer('benda_is_good')->default(0);
            $table->integer('benda_is_bad')->default(0);
            $table->integer('benda_is_all')->default(0);
            $table->integer('benda_last3month')->default(0);
            $table->integer('pengunjung')->default(0);
            $table->integer('all_user')->default(0);
            $table->integer('mutasi_process')->default(0);
            $table->integer('ram_used')->default(0);
            $table->integer('ram_total')->default(0);
            $table->timestamps();
        });

        Capsule::schema()->create('dashboard_stats_category_benda', function ($table) {
            $table->bigIncrements('dashboard_stats_category_benda_id');
            $table->bigInteger('dashboard_stats_id');
            $table->integer('kategory_id');
            $table->integer('total')->default(0);
        });

        Capsule::schema()->create('dashboard_stats_department_benda', function ($table) {
            $table->bigIncrements('dashboard_stats_department_benda_id');
            $table->bigInteger('dashboard_stats_id');
            $table->integer('department_id');
            $table->integer('total')->default(0);
        });

        Capsule::schema()->create('dashboard_stats_benda_tagging', function ($table) {
            $table->bigIncrements('dashboard_stats_benda_tagging_id');
            $table->bigInteger('dashboard_stats_id');
            $table->integer('tag_id');
            $table->integer('total')->default(0);
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists('dashboard_stats');
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
