<?php

namespace Fjord\Crud;

use Fjord\Crud\Fields\Concerns\FieldHasRules;
use Illuminate\Foundation\AliasLoader;
use Fjord\Support\Facades\Form as FormFacade;
use Fjord\Crud\Models\Relations\CrudRelations;
use Illuminate\Support\ServiceProvider;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
