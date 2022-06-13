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
        Schema::create('tourism_temporaries', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('place_id');
            $table->float('place_rating');
            $table->string('place_name');
            $table->string('category');
            $table->string('price');
            $table->float('rating');
            $table->float('time_minutes');
            $table->integer('pengguna');
            $table->integer('tempat');
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
        Schema::dropIfExists('tourism_temporaries');
    }
};
