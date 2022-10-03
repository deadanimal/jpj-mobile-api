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
        Schema::create('log_transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->string('device')->nullable();
            $table->date('tarikh')->nullable();
            $table->integer('direction')->nullable();
            $table->string('module')->nullable();
            $table->string('data')->nullable();
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
        Schema::dropIfExists('log_transaksis');
    }
};
