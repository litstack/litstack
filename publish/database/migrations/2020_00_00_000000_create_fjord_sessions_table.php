<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFjordSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fjord_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('session_id')->unique();
            $table->unsignedBigInteger('fjord_user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload')->nullable();
            $table->timestamp('last_activity', 0)->nullable();
            $table->timestamp('first_activity', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fjord_sessions');
    }
}
