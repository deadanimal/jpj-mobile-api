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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('nama');
            $table->string('nokp')->nullable();
            $table->string('kategori')->nullable();
            $table->string('dob')->nullable();
            $table->text('alamat1')->nullable();
            $table->text('alamat2')->nullable();
            $table->text('alamat3')->nullable();
            $table->string('poskod')->nullable();
            $table->string('bandar')->nullable();
            $table->string('negeri')->nullable();
            $table->string('nama_negeri')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('telefon')->nullable();
            $table->rememberToken();
            $table->integer('status_aktif')->nullable();
            $table->string('onesignal_id')->nullable();
            $table->string('uuid')->nullable();
            $table->string('last_login')->nullable();
            $table->string('tamat_lesen_memandu')->nullable();
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
        Schema::dropIfExists('users');
    }
};
