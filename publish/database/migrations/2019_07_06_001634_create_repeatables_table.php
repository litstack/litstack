<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepeatablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repeatables', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('page_name')->nullable();
            $table->string('block_name')->nullable();

            $table->string('type')->nullable();
            $table->text('content')->nullable();

            $table->unsignedInteger('order_column')->nullable();
            $table->boolean('active')->default(true);

            $table->timestamps();
        });

        Schema::create('repeatable_translations', function (Blueprint $table) {
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
        Schema::dropIfExists('repeatables');
        Schema::dropIfExists('repeatable_translations');
    }
}
