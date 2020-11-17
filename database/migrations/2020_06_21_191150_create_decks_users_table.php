<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecksUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decks_users', function (Blueprint $table) {
            $table->id();

            $table->string('nombredeck');
            $table->string('username');
            $table->string('twitter')->nullable();
            $table->string('crearkey')->nullable();
            $table->string('crearsecret')->nullable();
            $table->string('borrarkey')->nullable();
            $table->string('borrarsecret')->nullable();
            $table->string('followers')->nullable();
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
        Schema::dropIfExists('decks_users');
    }
}
