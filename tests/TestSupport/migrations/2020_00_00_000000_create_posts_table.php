<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Fjord\Support\Migration\MigratePermissions;

class CreatePostsTable extends Migration
{
    use MigratePermissions;

    /**
     * Permissions that should be created for this crud.
     *
     * @var array
     */
    protected $permissions = [
        'create posts',
        'read posts',
        'update posts',
        'delete posts',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
        });
        Schema::create('t_post_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('translatable_post_id')->unsigned();
            $table->string('locale')->index();

            $table->text('title')->nullable();
            $table->text('text')->nullable();

            $table->unique(['translatable_post_id', 'locale']);
            $table->foreign('translatable_post_id')->references('id')->on('t_posts')->onDelete('cascade');
        });
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('title')->nullable();
            $table->text('text')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });

        //$this->upPermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('t_posts');
        Schema::dropIfExists('t_post_translations');
        //$this->downPermissions();
    }
}
