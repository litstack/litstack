<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_content', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('page_name')->nullable();
            $table->string('field_name')->nullable();

            $table->text('content')->nullable();

            $table->unsignedInteger('order_column')->nullable();
            $table->boolean('active')->default(true);

            $table->timestamps();
        });

        Schema::create('page_content_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('page_content_id')->unsigned();
            $table->string('locale')->index();

            $table->text('content')->nullable();

            $table->unique(['page_content_id', 'locale']);
            $table->foreign('page_content_id')->references('id')->on('page_content')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_content');
        Schema::dropIfExists('page_content_translations');
    }
}
