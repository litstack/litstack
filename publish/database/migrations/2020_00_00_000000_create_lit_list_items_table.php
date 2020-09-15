<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLitListItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lit_list_items', function (Blueprint $table) {
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

        Schema::create('lit_list_item_translations', function (Blueprint $table) {
            $table->translates('lit_list_items', 'lit_list_item_id');
            $table->text('value')->nullable();
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
        Schema::dropIfExists('lit_list_items');
        Schema::dropIfExists('lit_list_item_translations');
        Schema::enableForeignKeyConstraints();
    }
}
