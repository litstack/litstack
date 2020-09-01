<?php

namespace Ignite\Support\Macros;

use Ignite\Crud\BaseForm;
use Ignite\Support\Facades\Lit;
use ParsedownExtra;

class FormMarkdown
{
    /**
     * Create new FormMarkdown instance.
     *
     * @return void
     */
    public function __construct()
    {
        BaseForm::macro('markdown', function (string $markup) {
            Lit::script(lit()->url('js/prism.js'));

            $parsed = (new ParsedownExtra)->text($markup);

            return $this->component('lit-field-markdown')->prop('markdown', $parsed);
        });
    }
}
