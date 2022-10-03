<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aktivitis', function (Blueprint $table) {
            $table->id();
            $table->string('transid_aktiviti')->nullable();
            $table->string('transid_sesi')->nullable();
            $table->string('nama_aktiviti')->nullable();
            $table->date('tarikh')->nullable();
            $table->time('masa')->nullable();
            $table->date('tarikh_mula')->nullable();
            $table->date('tarikh_tamat')->nullable();
            $table->time('masa_mula')->nullable();
            $table->time('masa_tamat')->nullable();
            $table->integer('bilangan_hari')->nullable();
            $table->integer('bilangan_sesi')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('aktivitis');
    }
};
