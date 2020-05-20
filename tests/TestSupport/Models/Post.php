<?php

namespace FjordTest\TestSupport\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = ['title', 'text', 'user_id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    public function many_relation_post()
    {
        return $this->manyRelation(self::class, 'many_relation_post');
    }

    public function one_relation_post()
    {
        return $this->oneRelation(self::class, 'one_relation_post');
    }
    */
}
