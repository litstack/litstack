<?php

namespace AwStudio\Fjord\Fjord;

use AwStudio\Fjord\Models\Repeatable;
use AwStudio\Fjord\Models\PageContent;
use Schema;

class Fjord
{
    use Concerns\ManagesPackages,
        Concerns\ManagesNavigation,
        Concerns\ManagesForms,
        Concerns\ManagesFiles;

    public $translatedAttributes = [];

    protected $langPaths = [];

    public function __construct()
    {
        $this->loadPackageManifest();
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
