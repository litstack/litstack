<?php

namespace Lit\Crud\Fields\Media;

use Lit\Crud\Fields\Traits\HasBaseField;
use Lit\Crud\Fields\Traits\TranslatableField;

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
