<?php

namespace FjordTest\TestSupport\Models;

use Illuminate\Database\Eloquent\Model;
use Fjord\Crud\Models\Traits\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use FjordTest\TestSupport\Models\Translations\TranslatablePostTranslation;

class TranslatablePost extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 't_posts';

    protected $translationModel = TranslatablePostTranslation::class;

    /**
     * Fillable attributes
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
