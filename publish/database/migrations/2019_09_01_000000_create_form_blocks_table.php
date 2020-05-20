<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('model_type')->nullable();
            $table->bigInteger('model_id')->nullable();
            $table->string('field_id')->nullable();

            $table->string('type')->nullable();
            $table->text('value')->nullable();

            $table->unsignedInteger('order_column')->nullable();

            $table->timestamps();
        });

        Schema::create('form_block_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('form_block_id')->unsigned();
            $table->string('locale')->index();

            $table->text('value')->nullable();

            $table->unique(['form_block_id', 'locale']);
            $table->foreign('form_block_id')->references('id')->on('form_blocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('form_blocks');
        Schema::dropIfExists('form_block_translations');
        Schema::enableForeignKeyConstraints();
    }
}
