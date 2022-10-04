<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_RencanaKerja extends CI_Migration
{
    public function up()
    {
        Capsule::schema()->create('rencana_kerja', function ($table) {
            $table->bigIncrements('rencana_kerja_id');
            $table->string('name', 100);
            $table->year('rk_year');
            $table->tinyInteger('rk_month1');
            $table->tinyInteger('rk_month2');
            $table->timestamps();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
        });

        Capsule::schema()->create('rencana_kerja_anggaran', function ($table) {
            $table->bigIncrements('rencana_kerja_anggaran_id');
            $table->bigInteger('rencana_kerja_id');
            $table->tinyInteger('rka_month');
        });

        Capsule::schema()->create('rencana_kerja_anggaran_budget', function ($table) {
            $table->bigIncrements('rencana_kerja_anggaran_budget_id');
            $table->bigInteger('fin_coa_group_id');
            $table->double('total', 15, 2)->default(0.00);
        });

        Capsule::schema()->create('rencana_kerja_anggaran_budget_sub', function ($table) {
            $table->bigIncrements('rencana_kerja_anggaran_budget_sub_id');
            $table->bigInteger('rencana_kerja_anggaran_budget_id');
            $table->string('name', 255);
            $table->double('value', 15, 2)->default(0.00);
            $table->integer('volume');
            $table->double('total', 15, 2)->default(0.00);
        });

        Capsule::schema()->create('rk_status', function ($table) {
            $table->bigIncrements('rk_status_id');
            $table->bigInteger('rencana_kerja_anggaran_budget_id');
            $table->enum('status', ['-1', '0', '1', '2'])->comment('-1  = reject, 0 = tidak dijawab, 1 = approved , 2 = diapprove sebagian ');
            $table->double('total', 15, 2)->default(0.00);
        });

        Capsule::schema()->create('realisasi_rka', function ($table) {
            $table->bigIncrements('realisasi_rka_id');
            $table->bigInteger('rencana_kerja_anggaran_budget_sub_id');
            $table->enum('status', ['-1', '0', '1', '2'])->comment('-1  = cancel , 1 = terpenuhi , 2 = Terpenuhi dan bersisa, -2 = berkurang ');
            $table->text('desc')->nullable(true)->default(null);
            $table->double('value_total', 15, 2)->default(0.00);
            $table->double('sisa', 15, 2)->default(0.00);
            $table->double('berkurang', 15, 2)->default(0.00);
            $table->timestamps();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists('rencana_kerja');
        Capsule::schema()->dropIfExists('rencana_kerja_anggaran');
        Capsule::schema()->dropIfExists('rencana_kerja_anggaran_budget');
        Capsule::schema()->dropIfExists('rencana_kerja_anggaran_budget_sub');
        Capsule::schema()->dropIfExists('rk_status');
        Capsule::schema()->dropIfExists('realisasi_rka');
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
