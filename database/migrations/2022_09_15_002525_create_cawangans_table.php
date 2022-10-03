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
        Schema::create('cawangans', function (Blueprint $table) {
            $table->id();
            $table->string('id_cawangan')->nullable();
            $table->string('nama_cawangan')->nullable();
            $table->string('negeri')->nullable();
            $table->string('daerah')->nullable();
            $table->integer('bil_kaunter')->nullable();
            $table->string('player_id')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->string('update_by')->nullable();
            $table->integer('sela_masa')->nullable();
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
        Schema::dropIfExists('cawangans');
    }
};
