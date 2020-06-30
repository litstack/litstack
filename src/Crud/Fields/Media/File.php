<?php

namespace Fjord\Crud\Fields\Media;

use Fjord\Crud\Fields\Traits\HasBaseField;
use Fjord\Crud\Fields\Traits\TranslatableField;

class File extends MediaField
{
    use TranslatableField;
    use HasBaseField;

    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        $this->setAttribute('type', 'file');
        $this->maxFileSize(12);
        $this->maxFiles(5);
        $this->override(false);
        $this->sortable(true);
        $this->accept('application/pdf');
    }
}
