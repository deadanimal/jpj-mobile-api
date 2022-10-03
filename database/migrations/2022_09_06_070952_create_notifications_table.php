<?php

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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->text('tajuk')->nullable();
            $table->text('perkara')->nullable();
            $table->string('tarikh_hantar')->nullable();
            $table->string('penerima')->nullable();
            $table->string('pengirim')->nullable();
            $table->string('onesigna_id')->nullable();
            $table->integer('jenis_noti')->nullable();
            $table->string('id_aduan')->nullable();
            $table->string('status_aduan')->nullable();
            $table->text('rujukan')->nullable();
            $table->integer('read_status')->nullable();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('notifications');
    }
};
