<?php

namespace Fjord\Test\TestSupport\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class TranslatablePostTranslation extends Model
{
    protected $table = 't_post_translations';

    public $timestamps = false;

    protected $fillable = ['title', 'text'];
}
