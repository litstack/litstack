<?php

namespace Fjord\Fjord;

use Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;

class Fjord
{
    use Concerns\ManagesForms;

    public $translatedAttributes = [];

    protected $langPaths = [];

    /**
     * Get Fjord application.
     *
     * @return \Fjord\Application\Application $app
     */
    public function app()
    {
        return app()->get('fjord.app');
    }

    /**
     * Binding composer to fjord::app view.
     * 
     * @param string $composer
     * @return void
     */
    public function composer($composer)
    {
        View::composer('fjord::app', $composer);
    }

    /**
     * Register extension class.
     * 
     * @param string $component
     * @param string $extension
     * @return void
     */
    public function registerExtension($component, $extension)
    {
        app()->get('fjord.app')->registerExtension($component, $extension);
    }

    /**
     * Prepare form fields.
     *
     * @param $fields
     * @param $path
     * @param $setDefaults
     * @return void
     */
    protected function prepareFields($fields, $path, $setDefaults = null)
    {
        foreach ($fields as $key => $field) {
            $fields[$key] = new FormFields\FormField($field, $path, $setDefaults);
        }
        return form_collect($fields);
    }

    /**
     * Get translation for Fjord application.
     *
     * @param string $key
     * @param array $replace
     * @return string
     */
    public function trans(string $key = null, $replace = [])
    {
        if (is_null($key)) {
            return $key;
        }

        return $this->app()->get('translator')->trans($key, $replace);
    }

    /**
     * Get translation for Fjord application.
     *
     * @param string $key
     * @param array $replace
     * @return string
     */
    public function __(string $key = null, $replace = [])
    {
        return $this->trans($key, $replace);
    }

    /**
     * Get authenticated Fjord user.
     *
     * @return \Fjord\Models\FjordUser $user
     */
    public function user()
    {
        return fjord_user();
    }

    /**
     * Get locale for Fjord application.
     *
     * @return void
     */
    public function getLocale()
    {
        return $this->app()->get('translator')->getLocale();
    }

    /**
     * Add language path for Fjord application translation.
     *
     * @param string $path
     * @return void
     */
    public function addLangPath(string $path)
    {
        if (!$this->installed()) {
            return false;
        }

        $this->app()->get('translator')->addPath($path);
    }

    /**
     * Checks if fjord has been installed.
     * 
     * @return boolean
     */
    public function installed()
    {
        if (!config()->has('fjord')) {
            return false;
        }

        if (!File::exists(app_path('Fjord/Kernel.php'))) {
            return false;
        }

        try {
            return Schema::hasTable('form_fields');
        } catch (\Exception $e) {
            return false;
        }
    }
}
