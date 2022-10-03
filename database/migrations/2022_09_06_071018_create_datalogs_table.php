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
        Schema::create('datalog', function (Blueprint $table) {
            $table->id();
            $table->string('jenis')->nullable();
            $table->string('jenis_data')->nullable();
            $table->string('ip')->nullable();
            $table->string('services')->nullable();
            $table->string('function')->nullable();
            $table->string('soapurl')->nullable();
            $table->text('data')->nullable();
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
        Schema::dropIfExists('datalog');
    }
};
