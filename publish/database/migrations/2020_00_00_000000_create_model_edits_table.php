<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelEditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_edits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fjord_user_id');
            $table->string('model_type');
            $table->string('model_id');
            $table->text('payload');
            $table->timestamp('created_at');

            $table->foreign('fjord_user_id')->references('id')->on('fjord_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('latest_model_edits');
    }
}
