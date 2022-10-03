<?php

use App\Models\MaklumatKenderaan;
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
        Schema::create('status_lesen', function (Blueprint $table) {
            $table->id();
            $table->string('nokp')->nullable();
            $table->string('jenis_lesen')->nullable();
            $table->string('tarikh_tamat')->nullable();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId(MaklumatKenderaan::class)->nullable();
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
        Schema::dropIfExists('status_lesen');
    }
};
