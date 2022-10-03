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
        Schema::create('urusetias', function (Blueprint $table) {
            $table->id();
            $table->integer('id_aktiviti')->nullable();
            $table->string('transid_aktiviti')->nullable();
            $table->string('urusetia')->nullable();
            $table->string('create_by')->nullable();
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
        Schema::dropIfExists('urusetias');
    }
};
