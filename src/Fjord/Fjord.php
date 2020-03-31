<?php

namespace AwStudio\Fjord\Fjord;

use Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use AwStudio\Fjord\Models\Repeatable;
use AwStudio\Fjord\Models\PageContent;

class Fjord
{
    use Concerns\ManagesForms;

    public $translatedAttributes = [];

    protected $langPaths = [];

    protected $app;

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
        app()->get('fjord.kernel')->registerExtension($component, $extension);
    }

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

        return __($key, $replace, $this->getLocale());
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

    public function getLocale()
    {
        return fjord_user()->locale ?? config('fjord.fallback_locale');
    }

    public function addLangPath($path)
    {
        $this->langPaths[] = $path;
    }

    public function getLangPaths()
    {
        return $this->langPaths;
    }

    /**
     * Checks if fjord has been installed.
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
