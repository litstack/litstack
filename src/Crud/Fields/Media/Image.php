<?php

namespace Ignite\Crud\Fields\Media;

use Ignite\Crud\Fields\Traits\HasBaseField;
use Ignite\Crud\Fields\Traits\TranslatableField;

class Image extends MediaField
{
    use TranslatableField;
    use HasBaseField;

    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function mount()
    {
        $this->setAttribute('type', 'image');
        $this->maxFileSize(12);
        $this->maxFiles(5);
        $this->crop(false);
        $this->override(false);
        $this->firstBig(false);
        $this->sortable(true);
        $this->showFullImage(false);
        $this->accept('image/*');
    }

    /**
     * Set showFullImage.
     *
     * @param bool $showFullImage
     *
     * @return $this
     */
    public function showFullImage(bool $showFullImage = true)
    {
        $this->setAttribute('showFullImage', $showFullImage);

        return $this;
    }

    /**
     * Set expand.
     *
     * @param bool $sortable
     *
     * @return $this
     */
    public function expand(bool $expand = true)
    {
        $this->setAttribute('expand', $expand);

        return $this;
    }

    /**
     * Set crop ratio.
     *
     * @param  bool|float $ratio
     * @return $this
     */
    public function crop($ratio = 0)
    {
        $this->setAttribute('crop', $ratio);

        return $this;
    }

    /**
     * Set firstBig.
     *
     * @param bool $firstBig
     *
     * @return $this
     */
    public function firstBig(bool $firstBig = true)
    {
        $this->setAttribute('firstBig', $firstBig);

        return $this;
    }
}
