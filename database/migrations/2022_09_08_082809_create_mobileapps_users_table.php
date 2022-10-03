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
        Schema::create('mobileapps_users', function (Blueprint $table) {
            $table->id();

            $table->string('username',50)->nullable();
            $table->string('katalaluan',200)->nullable();
            $table->string('nama',200)->nullable();
            $table->string('nokp',50)->nullable();
            $table->string('kategori',10)->default('1');
            $table->date('dob')->nullable();
            $table->text('alamat1')->nullable();
            $table->text('alamat2')->nullable();
            $table->text('alamat3')->nullable();
            $table->string('poskod',10)->nullable();
            $table->string('bandar',50)->nullable();
            $table->string('negeri',10)->nullable();
            $table->string('nama_negeri',50)->nullable();
            $table->string('emel',200)->nullable();
            $table->string('telefon',50)->nullable();
            $table->string('token',200)->nullable();
            $table->integer('status_aktif',)->default('1');
            $table->string('onesignal_id',200)->nullable();
            $table->string('uuid',200)->nullable();
            $table->datetime('last_login')->nullable();
            $table->date('tamat_lesen_memandu')->nullable();

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
        Schema::dropIfExists('mobileapps_users');
    }
};
