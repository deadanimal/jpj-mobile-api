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
        Schema::create('aduan', function (Blueprint $table) {
            $table->id();

            $table->string('no_aduan')->nullable();
            $table->string('pengadu')->nullable();
            $table->integer('jenis_kesalahan',)->nullable();
            $table->string('tarikh_kesalahan')->nullable();
            $table->string('masa_kesalahan')->nullable();
            $table->text('lokasi_kesalahan')->nullable();
            $table->string('longitude',50)->nullable();
            $table->string('latitude',50)->nullable();
            $table->string('no_kenderaan',50)->nullable();
            $table->string('nama_fail',100)->nullable();
            $table->string('pautan',250)->nullable();
            $table->string('jenis_media',50)->default('photo');
            $table->integer('status_aduan',)->default('0');
            $table->string('negeri',200)->nullable();
            $table->string('kod_negeri',10)->nullable();
            $table->string('kod_cawangan',10)->nullable();
            $table->text('catatan')->nullable();
            $table->string('device_id',200)->nullable();
            $table->string('onesignal_id',200)->nullable();
            $table->string('delete_date')->nullable();
            $table->integer('send_flag',)->default('99');
            $table->string('update_by',20)->nullable();

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
        Schema::dropIfExists('aduans');
    }
};
