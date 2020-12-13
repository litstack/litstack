<?php

namespace Tests\Crud\Fixtures;

use Ignite\Crud\CrudShow;
use Ignite\Crud\Config\CrudConfig;
use Tests\Crud\Fixtures\DummyPost;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class DummyPost extends Model
{
    public $table = 'posts';
    public $timestamps = false;
    public $fillable = ['text'];

    public static function schemaUp()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('text')->nullable();
        });
	}
	
	public static function schemaDown()
    {
        Schema::dropIfExists('posts');
    }
}
