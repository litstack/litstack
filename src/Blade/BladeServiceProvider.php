<?php

namespace AwStudio\Fjord\Blade;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Register Fjord Blade Directives
         *
         */
        Blade::directive('block', function($expression){
            return "<?php
                    \$loop = (object) [
                        'iteration' => 0
                    ];
                    foreach (\$repeatables[($expression)] as \$repeatable) {
                        \$view = 'repeatables.'.\$repeatable->type;
                        echo \$__env->make(\$view, array_except(get_defined_vars(), ['__data', '__path']))->render();
                        \$loop->iteration++;
                    }
                    ?>";
        });
        Blade::directive('repeatable', function($expression){
            return "<?php
                    echo \$repeatable->content[($expression)];
                    ?>";
        });
        Blade::directive('imageUrl', function($expression){
            return "<?php
                    echo \$repeatable->getFirstMediaUrl('image', ($expression));
                    ?>";
        });
        
        Blade::directive('field', function($expression){
            return "<?php
                    echo \$content->where('field_name', ($expression))->first()->content;
                    ?>";
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
