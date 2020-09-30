<?php

namespace Lit\Config\User;

use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\Config\Traits\ConfiguresProfileSettings;
use Ignite\Crud\CrudShow;
use Lit\Http\Controllers\User\ProfileSettingsController;
use Lit\Models\User;

class ProfileSettingsConfig extends CrudConfig
{
    use ConfiguresProfileSettings;

    /**
     * Model class.
     *
     * @var string
     */
    public $model = User::class;

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
        return [
            'singular' => $this->title(),
            'plural'   => $this->title(),
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
     * @param  \Ignite\Crud\CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        // settings
        $this->settings($page);

        // language
        $this->language($page);

        // security
        $this->security($page);
    }
}
