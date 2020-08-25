<?php

namespace Ignite\Crud\Fields\Media;

use Ignite\Crud\Fields\Traits\HasBaseField;
use Ignite\Crud\Fields\Traits\TranslatableField;

class File extends MediaField
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
        $this->setAttribute('type', 'file');
        $this->maxFileSize(12);
        $this->maxFiles(5);
        $this->override(false);
        $this->sortable(true);
        $this->accept('application/pdf');
    }
}
