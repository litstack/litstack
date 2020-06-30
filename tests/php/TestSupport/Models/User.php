<?php

namespace FjordTest\TestSupport\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
