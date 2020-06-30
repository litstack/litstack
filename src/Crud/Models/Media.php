<?php

namespace Fjord\Crud\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as MediaModel;

class Media extends MediaModel
{
    /**
     * Appends.
     *
     * @var array
     */
    protected $appends = ['url', 'conversion_urls'];

    /**
     * Get url attribute.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->getUrl();
    }

    /**
     * Get conversion urls.
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

    /**
     * Get custom_properties as attribute.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getAttribute($name)
    {
        $attribute = parent::getAttribute($name);

        if ($attribute) {
            return $attribute;
        }

        $properties = parent::getAttribute('custom_properties') ?? [];
        if (array_key_exists($name, $properties)) {
            return $properties[$name];
        }
    }
}
