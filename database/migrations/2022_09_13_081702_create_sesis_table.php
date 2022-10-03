<?php

use App\Models\Aktiviti;
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
        Schema::create('sesis', function (Blueprint $table) {
            $table->id();
            $table->string('transid_aktiviti')->nullable();
            $table->string('transid_sesi')->nullable();
            $table->integer('sesi')->nullable();
            $table->time('masa_mula')->nullable();
            $table->time('masa_tamat')->nullable();
            $table->integer('status_aktif')->nullable();
            $table->foreignIdFor(Aktiviti::class)->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('sesis');
    }
};
