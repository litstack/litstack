<?php

namespace AwStudio\Fjord\Fjord;

use AwStudio\Fjord\Models\Repeatable;
use AwStudio\Fjord\Models\PageContent;
use Illuminate\Support\Facades\View;
use Schema;

class Fjord
{
    use Concerns\ManagesNavigation,
        Concerns\ManagesForms,
        Concerns\ManagesFiles;

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
        foreach($fields as $key => $field) {
            $fields[$key] = new FormFields\FormField($field, $path, $setDefaults);
        }
        return form_collect($fields);
    }

    public function routes()
    {
        require fjord_path('routes/fjord.php');
    }

    public function trans($key = null, $replace = [])
    {
        if (is_null($key)) {
            return $key;
        }

        return __($key, $replace, $this->getLocale());
    }

    public function getLocale()
    {
        return fjord_user()->locale ?? config('fjord.fallback_locale');
    }

    public function addLangPath($path)
    {
        $this->langPaths []= $path;
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
        if(! config()->has('fjord')) {
            return false;
        }

        try {
            return Schema::hasTable('form_fields');
        } catch(\Exception $e) {
            return false;
        }
    }
}
