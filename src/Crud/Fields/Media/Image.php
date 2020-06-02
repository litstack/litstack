<?php

namespace Fjord\Crud\Fields\Media;

use Fjord\Crud\MediaField;
use Fjord\Crud\Fields\Traits\HasBaseField;
use Fjord\Crud\Fields\Traits\TranslatableField;

class Image extends MediaField
{
    use TranslatableField, HasBaseField;

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
        $this->imageSize(12);
        $this->maxFiles(5);
        $this->crop(false);
        $this->override(false);
        $this->firstBig(false);
        $this->sortable(true);
        $this->showFullImage(false);
    }

    /**
     * Set showFullImage.
     *
     * @param bool $showFullImage
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function imageSize(int $size)
    {
        $this->setAttribute('imageSize', $size);

        return $this;
    }
}
