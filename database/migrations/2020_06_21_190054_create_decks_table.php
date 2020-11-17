<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decks', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('admin');
            $table->string('rt');
            $table->string('descripcion');
            $table->string('crearkey')->nullable();
            $table->string('crearsecret')->nullable();
            $table->string('borrarkey')->nullable();
            $table->string('borrarsecret')->nullable();
            $table->string('numero')->nullable();
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
        Schema::dropIfExists('decks');
    }
}
