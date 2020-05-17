<?php

namespace Fjord\Support\Macros;

use Fjord\Crud\BaseForm;

class FormMarkdown
{
    /**
     * Create new WhereLike instance.
     * 
     * @return void
     */
    public function __construct()
    {
        BaseForm::macro('markdown', function (string $markup) {
            return $this->component('fj-field-markdown')
                ->prop('markdown', $markup);
        });
    }
}
