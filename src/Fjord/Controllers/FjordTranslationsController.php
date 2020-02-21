<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class FjordTranslationsController extends Controller
{
    public function __invoke()
    {
        $locales = array_diff(scandir(fjord_path('resources/lang/')), array('..', '.'));
        $translations = [];

        foreach ($locales as $locale) {
            $content = require(fjord_path('resources/lang/'.$locale.'/fjord.php'));
            $translations[$locale] = $content;
        }

        $js = 'window.i18n = ' . json_encode($translations) . ';';
        return response($js, 200)
            ->header('Content-Type', 'text/javascript');
    }
}
