<?php

namespace Fjord\Crud\Fields\Media;

use Fjord\Crud\MediaField;
use Fjord\Crud\Fields\Concerns\FormItemWrapper;
use Fjord\Crud\Fields\Concerns\TranslatableField;

class Image extends MediaField
{
    use FormItemWrapper,
        TranslatableField;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-media';

    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        $this->setAttribute('imageSize', 12);
        $this->setAttribute('maxFiles', 5);
        $this->setAttribute('crop', false);
        $this->setAttribute('override', false);
        $this->setAttribute('firstBig', false);
        $this->setAttribute('sortable', true);
        $this->setAttribute('showFullImage', false);
    }

    /**
     * Set showFullImage.
     *
     * @param bool $showFullImage
     * @return self
     */
    public function showFullImage(bool $showFullImage = true)
    {
        $this->setAttribute('showFullImage', $showFullImage);

        return $this;
    }

    /**
     * Set sortable.
     *
     * @param bool $sortable
     * @return self
     */
    public function sortable(bool $sortable = true)
    {
        $this->setAttribute('sortable', $sortable);

        return $this;
    }

    /**
     * Set override.
     *
     * @param bool $override
     * @return self
     */
    public function override(bool $override = true)
    {
        $this->setAttribute('override', $override);

        return $this;
    }

    /**
     * Set crop ratio.
     *
     * @param float $ratio
     * @return self
     */
    public function crop(float $ratio)
    {
        $this->setAttribute('crop', $ratio);

        return $this;
    }

    /**
     * Set firstBig.
     *
     * @param boolean $firstBig
     * @return self
     */
    public function firstBig(bool $firstBig = true)
    {
        $this->setAttribute('firstBig', $firstBig);

        return $this;
    }

    /**
     * Set max files.
     *
     * @param integer $maxFiles
     * @return self
     */
    public function maxFiles(int $maxFiles)
    {
        $this->setAttribute('maxFiles', $maxFiles);

        return $this;
    }

    /**
     * Set max image size.
     *
     * @param integer $size
     * @return self
     */
    public function imageSize(int $size)
    {
        $this->setAttribute('imageSize', $size);

        return $this;
    }
}
