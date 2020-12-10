<?php

namespace Ignite\Crud\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as MediaModel;

class Media extends MediaModel
{
    /**
     * Appends.
     *
     * @var array
     */
    protected $appends = ['url', 'conversion_urls', 'original_url'];

    /**
     * Get url attribute.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->getUrl('preview');
    }

    /**
     * Get url attribute.
     *
     * @return string
     */
    public function getOriginalUrlAttribute()
    {
        return $this->getUrl();
    }

    /**
     * Determine if the media is cropped.
     *
     * @return bool
     */
    public function getIsCroppedAttribute()
    {
        if (! $this->custom_properties) {
            return false;
        }

        return  array_key_exists('crop', $this->custom_properties);
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
     * @param  string $name
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
