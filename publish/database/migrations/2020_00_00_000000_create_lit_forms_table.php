<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLitFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lit_forms', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('config_type');
            $table->string('form_type')->nullable();
            $table->string('collection')->nullable();
            $table->string('form_name')->nullable();

            $table->text('value')->nullable();

            $table->unsignedInteger('order_column')->nullable();

            $table->timestamps();
        });

        Schema::create('lit_form_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lit_form_id')->unsigned();
            $table->string('locale')->index();

            $table->text('value')->nullable();

            $table->unique(['lit_form_id', 'locale']);
            $table->foreign('lit_form_id')->references('id')->on('lit_forms')->onDelete('cascade');
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
        Schema::dropIfExists('lit_forms');
        Schema::dropIfExists('lit_form_translations');
        Schema::enableForeignKeyConstraints();
    }
}
