<?php

namespace Ignite\Crud\Config\Traits;

use Ignite\Crud\CrudShow;

trait ConfiguresProfileSettings
{
    /**
     * Get the config title.
     *
     * @return string
     */
    public function title()
    {
        return ucwords(__lit('base.item_settings', [
            'item' => __lit('base.profile'),
        ]));
    }

    /**
     * Build settings form.
     *
     * @param  CrudShow $page
     * @return void
     */
    public function settings(CrudShow $page)
    {
        $page->info(ucwords(__lit('base.general')))->width(4);
        $page->card(function ($form) {
            $form->input('first_name')
                ->width(6)
                ->title(ucwords(__lit('base.first_name')));

            $form->input('last_name')
                ->width(6)
                ->title(ucwords(__lit('base.last_name')));

            $form->modal('change_email')
                ->title('E-Mail')
                ->variant('primary')
                ->preview('{email}')
                ->name('Change E-Mail')
                ->confirmWithPassword()
                ->form(function ($modal) {
                    $modal->input('email')
                        ->width(12)
                        ->rules('required')
                        ->title('E-Mail');
                })->width(6);

            $form->input('username')
                ->width(6)
                ->title(ucwords(__lit('base.username')));

            $form->extend(static::class, 'settings');
        })->width(8)->class('mb-5');
    }

    /**
     * Change language.
     *
     * @param  CrudShow $form
     * @return void
     */
    public function language($form)
    {
        if (! config('lit.translatable.translatable')) {
            return;
        }

        $form->info(ucwords(__lit('base.language')))->width(4)
            ->text(__lit('profile.messages.language'));

        $form->card(fn ($form) => $form->component('lit-locales')->class('mb-4'))
            ->width(8)
            ->class('mb-5');
    }

    /**
     * User security.
     *
     * @param  CrudShow $page
     * @return void
     */
    public function security($page)
    {
        $page->group(function ($page) {
            $page->info(ucwords(__lit('base.security')));
        })->width(4);

        $page->group(function ($page) {
            $page->card(function ($form) {
                $form->modal('change_password')
                    ->title(ucfirst(__lit('base.password')))
                    ->variant('primary')
                    ->name(fa('user-shield').' '.__lit('profile.change_password'))
                    ->form(function ($modal) {
                        $modal->password('old_password')
                            ->title('Old Password')
                            ->confirm();

                        $modal->password('password')
                            ->title(ucfirst(__lit('base.password')))
                            ->rules('required', 'min:5')
                            ->minScore(0);

                        $modal->password('password_confirmation')
                            ->rules('required', 'same:password')
                            ->dontStore()
                            ->title(ucwords(__lit('profile.new_password')))
                            ->noScore();
                    });
            });

            $page->extend(static::class, 'security');
        })->width(8);
    }
}
