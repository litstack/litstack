<?php

namespace Fjord\Test\TestSupport\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = ['title', 'text', 'author_id'];

    public $timestamps = false;

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
