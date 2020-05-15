<?php

namespace FjordApp\Config\User;

use Fjord\Crud\CrudForm;
use Fjord\User\Models\FjordUser;
use Fjord\Crud\Config\Traits\HasCrudForm;

class ProfileSettingsConfig
{
    use HasCrudForm;

    /**
     * FjordUser Model.
     *
     * @var string
     */
    public $model = FjordUser::class;

    /**
     * Route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return fjord()->url('profile/settings');
    }

    /**
     * Setup profile settings.
     *
     * @param \Fjord\Crud\CrudForm $form
     * @return void
     */
    public function form(CrudForm $form)
    {
        $form->setRoutePrefix(
            strip_slashes($this->routePrefix())
        );

        $form->info(ucwords(__f('base.general')))->cols(4);

        $form->card(function ($form) {

            $form->input('first_name')
                ->cols(6)
                ->title(ucwords(__f('base.first_name')));

            $form->input('first_name')
                ->cols(6)
                ->title(ucwords(__f('base.first_name')));

            $form->modal('change_email')
                ->title('E-Mail')
                ->variant('primary')
                ->preview('{email}')
                ->name('Change E-Mail')
                ->confirmWithPassword()
                ->form(function ($modal) {
                    $modal->input('email')
                        ->cols(12)
                        ->rules('required')
                        ->title('E-Mail');
                })->cols(6);

            $form->input('username')
                ->cols(6)
                ->title(ucwords(__f('base.username')));
        })->cols(8)->class('mb-5');

        if (config('fjord.translatable.translatable')) {
            $form->info(ucwords(__f('base.language')))->cols(4)
                ->text(__f('profile.messages.language'));
            $form->card(function ($form) {
                $form->component('fj-locales')->class('mb-4');
            })->cols(8)->class('mb-5');
        }

        $form->info(ucwords(__f('base.security')))->cols(4);

        $form->card(function ($form) {
            $form->modal('change_password')
                ->title('Password')
                ->variant('primary')
                ->name(fa('user-shield') . ' ' . __f('profile.change_password'))
                ->form(function ($modal) {
                    $modal->password('old_password')
                        ->title('Old Password')
                        ->confirm();

                    $modal->password('password')
                        ->title('New Password')
                        ->rules('required', 'min:5')
                        ->minScore(0);

                    $modal->password('password_confirmation')
                        ->rules('required', 'same:password')
                        ->dontStore()
                        ->title('New Password')
                        ->noScore();
                });

            $form->component('fj-profile-security');
        })->cols(8);
    }
}
