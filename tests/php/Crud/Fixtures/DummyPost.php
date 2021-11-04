<?php

namespace Tests\Crud\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
