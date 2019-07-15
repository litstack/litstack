<?php

namespace AwStudio\Fjord\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class PageContent extends Model implements  TranslatableContract
{
    use Translatable;

    protected $table = 'page_content';
    protected $translationModel = Translations\PageContentTranslation::class;

    public $fillable = ['page_name', 'field_name', 'content'];
    public $translatedAttributes = ['content'];


}
