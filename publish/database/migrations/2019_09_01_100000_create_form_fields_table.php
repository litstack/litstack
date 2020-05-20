<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('collection')->nullable();
            $table->string('form_name')->nullable();

            $table->text('value')->nullable();

            $table->unsignedInteger('order_column')->nullable();

            $table->timestamps();
        });

        Schema::create('form_field_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('form_field_id')->unsigned();
            $table->string('locale')->index();

            $table->text('value')->nullable();

            $table->unique(['form_field_id', 'locale']);
            $table->foreign('form_field_id')->references('id')->on('form_fields')->onDelete('cascade');
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
        Schema::dropIfExists('form_fields');
        Schema::dropIfExists('form_field_translations');
        Schema::enableForeignKeyConstraints();
    }
}
