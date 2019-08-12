<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_content', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('model')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();

            $table->string('type')->nullable();
            $table->text('link')->nullable();
            $table->text('content')->nullable();

            $table->unsignedInteger('order_column')->nullable();
            $table->boolean('active')->default(true);

            $table->timestamps();
        });

        Schema::create('model_content_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('repeatable_id')->unsigned();
            $table->string('locale')->index();

            $table->text('content')->nullable();

            $table->unique(['repeatable_id', 'locale']);
            $table->foreign('repeatable_id')->references('id')->on('repeatables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_content');
        Schema::dropIfExists('model_content_translations');
    }
}
