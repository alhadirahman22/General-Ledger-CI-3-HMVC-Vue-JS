<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;

class Migration_Coa extends CI_Migration
{
    public function up()
    {

        Capsule::schema()->create('fin_coa_aktiva_passiva', function ($table) {
            $table->bigIncrements('fin_coa_aktiva_passiva_id');
            $table->string('name', 100);
        });

        $data_insert = [
            [
                'name' => 'Aktiva',
            ],
            [
                'name' => 'Passiva',
            ],
            [
                'name' => 'Non Aktiva Passiva',
            ],

        ];

        $this->db->insert_batch('fin_coa_aktiva_passiva', $data_insert);

        Capsule::schema()->create('fin_coa_aktiva_passiva_sub', function ($table) {
            $table->bigIncrements('fin_coa_aktiva_passiva_sub_id');
            $table->bigInteger('fin_coa_aktiva_passiva_id');
            $table->string('name', 100);
        });

        $data_insert = [
            [
                'fin_coa_aktiva_passiva_id' => 1,
                'name' => 'Aktiva Lancar',
            ],
            [
                'fin_coa_aktiva_passiva_id' => 1,
                'name' => 'Aktiva Tetap',
            ],
            [
                'fin_coa_aktiva_passiva_id' => 2,
                'name' => 'Hutang Lancar',
            ],
            [
                'fin_coa_aktiva_passiva_id' => 2,
                'name' => 'Hutang Jangka Panjang',
            ],
            [
                'fin_coa_aktiva_passiva_id' => 2,
                'name' => 'Modal',
            ],
            [
                'fin_coa_aktiva_passiva_id' => 3,
                'name' => 'Non Aktiva Passiva',
            ],

        ];
        $this->db->insert_batch('fin_coa_aktiva_passiva_sub', $data_insert);


        Capsule::schema()->create('fin_coa_group', function ($table) {
            $table->bigIncrements('fin_coa_group_id');
            $table->bigInteger('fin_coa_aktiva_passiva_sub_id')->nullable(true)->default(null);
            $table->string('fin_coa_group_code', 100)->comment('code')->unique();
            $table->string('fin_coa_group_name', 255);
            $table->string('fin_coa_group_prefix', 10);
            $table->string('fin_coa_group_inc', 10);
            $table->text('desc')->nullable(true);
            $table->timestamps();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
        });

        Capsule::schema()->create('fin_coa', function ($table) {
            $table->bigIncrements('fin_coa_id');
            $table->bigInteger('fin_coa_group_id');
            $table->string('fin_coa_code', 100)->comment('code')->unique();
            $table->string('fin_coa_code_inc', 10);
            $table->string('fin_coa_name', 255);
            $table->enum('type', ['D', 'C'])->comment('D = Debit, C= Credit');
            $table->enum('status', ['A', 'T'])->comment('A = Aktif, T= Tidak Aktif')->default('A');
            // $table->enum('anggaran', ['0', '1'])->comment('0 = Tidak masuk anggaran, 1= Masuk Anggaran')->default('0');
            $table->text('desc')->nullable(true);
            $table->timestamps();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
        });

        Capsule::schema()->create('fin_coa_saldo', function ($table) {
            $table->bigIncrements('fin_coa_saldo_id');
            $table->bigInteger('fin_coa_id');
            $table->double('value', 15, 2)->default(0.00);
            $table->date('date');
            $table->timestamps();
            $table->integer('created_by')->nullable(true);
        });

        Capsule::schema()->create('fin_coa_saldo_history', function ($table) {
            $table->bigIncrements('fin_coa_saldo_history_id');
            $table->bigInteger('fin_coa_saldo_id');
            $table->bigInteger('id_refer')->nullable(true)->default(null);
            $table->string('table_name', 100)->nullable(true)->default(null);
            $table->text('desc')->nullable(true)->default(null);
            $table->double('value', 15, 2)->default(0.00);
            $table->double('current_value', 15, 2)->default(0.00);
            $table->double('become_value', 15, 2)->default(0.00);
            $table->enum('type_value', ['1', '2'])->comment('1 = plus, 2 = minus');
            $table->date('date_trans');
            $table->timestamps();
            $table->integer('created_by')->nullable(true);
        });


        Capsule::schema()->create('fin_jurnal_voucher', function ($table) {
            $table->bigIncrements('fin_jurnal_voucher_id');
            $table->string('fin_jurnal_voucher_code', 100)->comment('code')->unique();
            $table->string('fin_jurnal_voucher_name', 255);
            $table->string('fin_jurnal_voucher_prefix', 10);
            $table->string('fin_jurnal_voucher_inc', 10);
            $table->text('desc')->nullable(true);
            $table->timestamps();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
        });

        Capsule::schema()->create('fin_gl', function ($table) {
            $table->bigIncrements('fin_gl_id');
            $table->bigInteger('fin_jurnal_voucher_id');
            $table->string('fin_gl_prefix', 10);
            $table->string('fin_gl_code', 100)->comment('code')->unique();
            $table->date('fin_gl_date', 100);
            $table->string('fin_gl_code_inc', 10);
            $table->string('fin_gl_no_bukti', 100);
            $table->float('debit_total', 15, 2)->nullable(true);
            $table->float('credit_total', 15, 2)->nullable(true);
            $table->float('selisih_total', 15, 2)->nullable(true);
            $table->timestamps();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
        });

        Capsule::schema()->create('fin_gl_detail', function ($table) {
            $table->bigIncrements('fin_gl_detail_id');
            $table->bigInteger('fin_gl_id');
            $table->bigInteger('fin_coa_id');
            $table->string('fin_gl_referensi', 100)->nullable(true);
            $table->float('debit', 15, 2)->nullable(true);
            $table->float('credit', 15, 2)->nullable(true);
            $table->text('desc')->nullable(true);
            $table->timestamps();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
        });

        Capsule::schema()->create('sia', function ($table) {
            $table->bigIncrements('sia_id');
            $table->bigInteger('fin_coa_id');
            $table->bigInteger('id_refer')->nullable(true)->default(null);
            $table->string('table_name', 100)->nullable(true)->default(null);
            $table->double('debit', 15, 2)->default(0.00);
            $table->double('credit', 15, 2)->default(0.00);
            $table->text('desc')->nullable(true)->default(null);
            $table->timestamps();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
        });
    }

    public function down()
    {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists('fin_coa_aktiva_passiva');
        Capsule::schema()->dropIfExists('fin_coa_aktiva_passiva_sub');
        Capsule::schema()->dropIfExists('fin_coa_group');
        Capsule::schema()->dropIfExists('fin_coa');
        Capsule::schema()->dropIfExists('fin_coa_saldo');
        Capsule::schema()->dropIfExists('fin_coa_saldo_history');
        Capsule::schema()->dropIfExists('fin_jurnal_voucher');
        Capsule::schema()->dropIfExists('fin_gl');
        Capsule::schema()->dropIfExists('fin_gl_detail');
        Capsule::schema()->dropIfExists('sia');
        Capsule::schema()->enableForeignKeyConstraints();
    }
}
