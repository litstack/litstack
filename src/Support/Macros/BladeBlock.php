<?php

namespace Fjord\Support\Macros;

use Illuminate\Support\Facades\Blade;

class BladeBlock
{
    /**
     * Register macro.
     *
     * @return void
     */
    public function register()
    {
        Blade::directive('block', fn ($expression) => $this->compileBlock($expression));
    }

    /**
     * Compile block directive.
     *
     * @param string $expression
     *
     * @return string
     */
    protected function compileBlock($expression)
    {
        return Blade::compileString("@include('fjord::partials.block', ['repeatables' => $expression])");
    }
}
