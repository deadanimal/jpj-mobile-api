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
        Schema::create('kumpulan_perkhidmatans', function (Blueprint $table) {
            $table->id();
            $table->string('id_perkhidmatan')->nullable();
            $table->string('id_cawangan')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('no_siri')->nullable();
            $table->integer('no_terkini')->nullable();
            $table->integer('kategori')->nullable();
            $table->integer('ispelbagai')->nullable();
            $table->integer('kaunter_id')->nullable();
            $table->integer('cawangan_id')->nullable();
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
        Schema::dropIfExists('kumpulan_perkhidmatans');
    }
};
