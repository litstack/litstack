<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormListItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_list_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('model_type')->nullable();
            $table->bigInteger('model_id')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->string('config_type');
            $table->string('form_type')->nullable();
            $table->string('field_id')->nullable();

            $table->text('value')->nullable();

            $table->unsignedInteger('order_column')->nullable();
            $table->boolean('active')->default(true);

            $table->timestamps();
        });

        Schema::create('form_list_item_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('form_list_item_id')->unsigned();
            $table->string('locale')->index();

            $table->text('value')->nullable();

            $table->unique(['form_list_item_id', 'locale']);
            $table->foreign('form_list_item_id')->references('id')->on('form_list_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_list_items');
    }
}
