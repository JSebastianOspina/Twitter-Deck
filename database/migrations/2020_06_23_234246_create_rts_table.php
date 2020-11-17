<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('rtid');
            $table->string('deck');
            $table->string('cuenta');
            $table->string('twitter');
            $table->string('pendiente');
            $table->string('cantidad');
            $table->string('quienes')->nullable();








        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rts');
    }
}
