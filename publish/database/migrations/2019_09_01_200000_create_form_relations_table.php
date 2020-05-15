<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('from_model_type');
            $table->bigInteger('from_model_id');
            $table->string('to_model_type');
            $table->bigInteger('to_model_id');
            $table->string('field_id');
            $table->unsignedInteger('order_column')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_relations');
    }
}
