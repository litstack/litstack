<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_content', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->nullable();
            $table->string('field_id')->nullable();

            $table->text('value')->nullable();

            $table->unsignedInteger('order_column')->nullable();
            $table->boolean('active')->default(true);

            $table->timestamps();
        });

        Schema::create('form_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('page_content_id')->unsigned();
            $table->string('locale')->index();

            $table->text('value')->nullable();

            $table->unique(['form_content_id', 'locale']);
            $table->foreign('form_content_id')->references('id')->on('form_content')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_content');
        Schema::dropIfExists('form_content_translations');
    }
}
