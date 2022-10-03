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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('services')->nullable();
            $table->string('function')->nullable();
            $table->string('ip')->nullable();
            $table->text('ref')->nullable();
            $table->text('agent')->nullable();
            $table->string('hostname')->nullable();
            $table->string('uuid')->nullable();
            $table->string('appv')->nullable();
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
        Schema::dropIfExists('logs');
    }
};
