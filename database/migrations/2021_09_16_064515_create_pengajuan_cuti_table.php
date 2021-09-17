<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanCutiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_cuti', function (Blueprint $table) {
            $table->string('nomor', 11)->primary();
            $table->integer('id_user');
            $table->integer('user_persetujuan')->nullable();
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->integer('jumlah_cuti');
            $table->text('keterangan');
            $table->enum('status_persetujuan', ['0', '1'])->nullable();
            $table->date('tanggal_persetujuan')->nullable();
            $table->text('ket_persetujuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan_cuti');
    }
}
