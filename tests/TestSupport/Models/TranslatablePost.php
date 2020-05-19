<?php

namespace Fjord\Test\TestSupport\Models;

use Illuminate\Database\Eloquent\Model;
use Fjord\Crud\Models\Traits\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Fjord\Test\TestSupport\Models\Translations\TranslatablePostTranslation;

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
}
