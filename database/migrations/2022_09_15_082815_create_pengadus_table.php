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
        Schema::create('pengadu', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('nama')->nullable();
            $table->string('nokp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('poskod')->nullable();
            $table->text('daerah')->nullable();
            $table->string('negeri')->nullable();
            $table->string('emel')->nullable();
            $table->string('telefon')->nullable();
            $table->string('token')->nullable();
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
        Schema::dropIfExists('pengadu');
    }
};
