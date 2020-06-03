<?php

namespace FjordTest\TestSupport\Models;

use Fjord\Crud\Models\Traits\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia as HasMediaContract;

class Post extends Model implements HasMediaContract
{
    use HasMedia;

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = ['title', 'text', 'user_id'];

    /**
     * Eger loads.
     *
     * @var array
     */
    protected $with = ['media'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTestImageAttribute()
    {
        return $this->getMedia('test_image')->first();
    }

    public function getTestImagesAttribute()
    {
        return $this->getMedia('test_images');
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
