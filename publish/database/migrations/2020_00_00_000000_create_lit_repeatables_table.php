<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLitRepeatablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lit_repeatables', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('model_type')->nullable();
            $table->bigInteger('model_id')->nullable();
            $table->string('config_type');
            $table->string('form_type')->nullable();
            $table->string('field_id')->nullable();

            $table->string('type')->nullable();
            $table->text('value')->nullable();

            $table->unsignedInteger('order_column')->nullable();

            $table->timestamps();
        });

        Schema::create('lit_repeatable_translations', function (Blueprint $table) {
            $table->translates('lit_repeatables', 'lit_repeatable_id');
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
        Schema::dropIfExists('lit_repeatables');
        Schema::dropIfExists('lit_repeatable_translations');
        Schema::enableForeignKeyConstraints();
    }
}
