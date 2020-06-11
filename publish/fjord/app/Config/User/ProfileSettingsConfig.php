<?php

namespace FjordApp\Config\User;

use Fjord\Crud\CrudShow;
use Fjord\User\Models\FjordUser;
use Fjord\Crud\Config\CrudConfig;
use FjordApp\Controllers\User\ProfileSettingsController;

class ProfileSettingsConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = FjordUser::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = ProfileSettingsController::class;

    /**
     * Model singular and plural name.
     *
     * @return array
     */
    public function names()
    {
        $profileSettingsName = ucwords(__f('base.item_settings', [
            'item' => __f('base.profile')
        ]));

        return [
            'singular' => $profileSettingsName,
            'plural' => $profileSettingsName,
        ];
    }

    /**
     * Route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'profile';
    }

    /**
     * Setup create and edit form.
     *
     * @param \Fjord\Crud\CrudShow $form
     * @return void
     */
    public function show(CrudShow $container)
    {
        // settings
        $container->info(ucwords(__f('base.general')))->width(4);
        $container->card(fn ($form) => $this->settings($form))
            ->width(8)->class('mb-5');

        // language
        $this->language($container);

        // security
        $container->info(ucwords(__f('base.security')))->width(4);

        $container->group(function ($container) {
            $container->card(fn ($form) => $this->security($form));
            $container->component('fj-profile-security');
        })->width(8);
    }

    /**
     * Profile settings.
     *
     * @param CrudShow $form
     * @return void
     */
    public function settings($form)
    {
        $form->input('first_name')
            ->width(6)
            ->title(ucwords(__f('base.first_name')));

        $form->input('last_name')
            ->width(6)
            ->title(ucwords(__f('base.last_name')));

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
            ->title(ucwords(__f('base.username')));
    }

    /**
     * Change language
     *
     * @param CrudShow $form
     * @return void
     */
    public function language($form)
    {
        if (!config('fjord.translatable.translatable')) {
            return;
        }

        $form->info(ucwords(__f('base.language')))->width(4)
            ->text(__f('profile.messages.language'));

        $form->card(fn ($form) => $form->component('fj-locales')->class('mb-4'))
            ->width(8)
            ->class('mb-5');
    }

    /**
     * User security.
     * - Change password
     * - Session manager
     *
     * @param CrudShow $container
     * @return void
     */
    public function security($form)
    {
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
    }
}
