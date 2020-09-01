<?php

namespace Ignite\Support\Macros;

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
     * @param  string $expression
     * @return string
     */
    protected function compileBlock($expression)
    {
        $expression = Blade::stripParentheses($expression);

        return "<?php echo \$__env->make('litstack::partials.block', ['repeatables' => $expression], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>";
    }
}
