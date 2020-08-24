<?php

namespace Lit\Support\Macros;

use Lit\Crud\BaseForm;
use Lit\Support\Facades\LitApp;
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
            LitApp::script(lit()->url('js/prism.js'));

            $parsed = (new ParsedownExtra)->text($markup);

            return $this->component('lit-field-markdown')->prop('markdown', $parsed);
        });
    }
}
