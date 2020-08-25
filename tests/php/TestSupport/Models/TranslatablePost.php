<?php

namespace Tests\TestSupport\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Ignite\Crud\Models\Traits\Translatable;
use Tests\TestSupport\Models\Translations\TranslatablePostTranslation;
use Illuminate\Database\Eloquent\Model;

class TranslatablePost extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 't_posts';

    protected $translationModel = TranslatablePostTranslation::class;

    /**
     * Fillable attributes.
     *
     * @var array
     */
    public $translatedAttributes = ['title', 'text'];

    public $timestamps = false;

    public function translatablePosts()
    {
        return $this->manyRelation(self::class, 'translatablePosts');
    }

    public function translatablePost()
    {
        return $this->oneRelation(self::class, 'translatablePost');
    }
}
