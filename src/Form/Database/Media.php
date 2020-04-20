<?php

namespace Fjord\Form\Database;

use Spatie\MediaLibrary\Models\Media as MediaModel;

class Media extends MediaModel
{
    /**
     * Appends.
     *
     * @var array
     */
    protected $appends = ['url', 'conversion_urls'];

    /**
     * Get media url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->getUrl();
    }

    /**
     * Get conversions urls.
     *
     * @return array
     */
    public function getConversionUrlsAttribute()
    {
        $urls = [];
        foreach ($this->getMediaConversionNames() as $name) {
            $urls[$name] = $this->getUrl($name);
        }
        return $urls;
    }
}
