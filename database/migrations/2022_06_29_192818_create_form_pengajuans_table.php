<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormPengajuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('jenis_pengajuan')->nullable();
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_berakhir')->nullable();
            $table->string('lampiran')->nullable(); // file
            $table->text('catatan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->char('status', 1)->nullable()->default(0);
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
        Schema::dropIfExists('form_pengajuan');
    }
}
