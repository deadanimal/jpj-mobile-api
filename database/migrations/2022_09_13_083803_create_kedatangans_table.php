<?php

use App\Models\Aktiviti;
use App\Models\User;
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
        Schema::create('kedatangans', function (Blueprint $table) {
            $table->id();
            $table->string('transid_aktiviti')->nullable();
            $table->string('transid_sesi')->nullable();
            $table->date('tarikh')->nullable();
            $table->time('masa')->nullable();
            $table->integer('id_aktiviti')->nullable();
            $table->string('nokp')->nullable();
            $table->string('kodbahagian')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('agensi')->nullable();

            $table->foreignIdFor(Aktiviti::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();

            $table->string('staf_id')->nullable();
            $table->string('staf_bahagian_id')->nullable();
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
        Schema::dropIfExists('kedatangans');
    }
};
