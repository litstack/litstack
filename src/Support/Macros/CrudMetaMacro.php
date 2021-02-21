<?php

namespace Ignite\Support\Macros;

use Ignite\Crud\CrudShow;

class CrudMetaMacro
{
    /**
     * Register macro.
     *
     * @return void
     */
    public function register()
    {
        CrudShow::macro('meta', function () {
            return $this->card(function ($card) {
                $metaMaxWith = '520px';
                $card->wrapper('lit-utilities-meta-wrapper', function ($meta) use ($metaMaxWith) {
                    $meta->input('meta_title')
                        ->title('Meta-Title')
                        ->translatable(lit()->isAppTranslatable())
                        ->placeholder('Meta-Title')
                        ->hint(__lit('crud.meta.title_hint', [
                            'width' => $metaMaxWith,
                        ]));

                    $meta->input('meta_keywords')
                        ->title('Meta-Keywords')
                        ->translatable(lit()->isAppTranslatable())
                        ->placeholder('Keyword1, Keyword2, …')
                        ->hint(__lit('crud.meta.keywords_hint'));

                    $meta->input('meta_description')
                        ->title('Meta-Beschreibung')
                        ->translatable(lit()->isAppTranslatable())
                        ->placeholder('Meta-Beschreibung')
                        ->hint(__lit('crud.meta.description_hint'))
                        ->max(156)
                        ->rules('max:156');

                    $meta->component('lit-utilities-meta');
                })->prop('google-meta-max-width', $metaMaxWith);
            })->title('Meta-Info');
        });
    }
}
