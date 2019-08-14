<?php

namespace AwStudio\Fjord\Models\Translations;

use Illuminate\Database\Eloquent\Model;

class PageContentTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['content'];

    public function model()
    {
        return $this->belongsTo('AwStudio\Fjord\Models\PageContent', 'page_content_id');
    }
}
