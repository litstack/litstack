<?php

namespace Fjord\Form\Database;

use Spatie\MediaLibrary\Models\Media as MediaModel;

class Media extends MediaModel
{
    protected $appends = ['url', 'conversion_urls'];

    public function getUrlAttribute()
    {
        return $this->getUrl();
    }

    public function getConversionUrlsAttribute()
    {
        $urls = [];
        foreach($this->getMediaConversionNames() as $name) {
            $urls[$name] = $this->getUrl($name);
        }
        return $urls;
    }
}
