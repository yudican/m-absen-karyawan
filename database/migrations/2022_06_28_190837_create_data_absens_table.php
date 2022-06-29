<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataAbsensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_absen', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jadwal_absen_id')->constrained('jadwal_absen')->cascadeOnDelete();
            $table->dateTime('waktu_absen');
            $table->string('foto_absen')->nullable();
            $table->char('status_absen', 1)->default(1); // 1=masuk;2=izin;3=cuti;4=sakit;5=telat
            $table->char('status_perizinan', 1)->default(0); // 0=tidak;1=ya
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
        Schema::dropIfExists('data_absen');
    }
}
