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
        Schema::create('bahagians', function (Blueprint $table) {
            $table->id();
            $table->string('kod')->nullable();
            $table->string('kod 2')->nullable();
            $table->string('kod 3')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('keterangan 2')->nullable();
            $table->integer('kategori')->nullable();
            $table->integer('susunan')->nullable();
            $table->integer('bilangan_hadir')->nullable();
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
        Schema::dropIfExists('bahagians');
    }
};
