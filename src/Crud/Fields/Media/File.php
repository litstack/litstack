<?php

namespace Fjord\Crud\Fields\Media;

use Fjord\Crud\Fields\Traits\HasBaseField;
use Fjord\Crud\Fields\Traits\TranslatableField;

class File extends MediaField
{
    use TranslatableField, HasBaseField;

    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        $this->fileSize(12);
        $this->maxFiles(5);
        $this->override(false);
        $this->sortable(true);
    }

    /**
     * Set crop ratio.
     *
     * @param boolean|float $ratio
     * @return $this
     */
    public function crop($ratio)
    {
        $this->setAttribute('crop', $ratio);

        return $this;
    }
}
