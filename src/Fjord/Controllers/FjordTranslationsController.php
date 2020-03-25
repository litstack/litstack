<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class FjordTranslationsController extends Controller
{
    public function translations()
    {
        $translations = $this->getTranslations();
        $js = 'window.i18n = ' . json_encode($translations) . ';';
        return response($js, 200)
            ->header('Content-Type', 'text/javascript');
    }

    protected function getTranslations()
    {
        if(! config('fjord.translatable.translatable')) {
            return [];
        }
        
        $locale = fjord_user()->locale ?? config('fjord.translatable.fallback_locale');

        $content = require(fjord_path('resources/lang/' . $locale . '/fjord.php'));
        $translations = $content;

        foreach (glob(resource_path('lang/' . $locale . '/*.php')) as $file) {
            $content = require($file);
            $key = explode('.', basename($file))[0];

            $translations[$key] = $content;
        }

        return [$locale => $translations];
    }
}
