<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FjordTranslationsController extends Controller
{
    protected $translations = [];

    public function __construct()
    {

        foreach(config('fjord.translatable.locales') as $locale) {
            $this->translations[$locale] = [];
        }
    }

    public function translations()
    {
        $this->getTranslations();
        $js = 'window.i18n = ' . json_encode($this->translations) . ';';
        return response($js, 200)
            ->header('Content-Type', 'text/javascript');
    }

    protected function getTranslations()
    {
        if(! config('fjord.translatable.translatable')) {
            return;
        }


        foreach(fjord()->getLangPaths() as $path) {
            foreach(config('fjord.translatable.locales') as $locale) {
                $dir = realpath($path . '/' . $locale);
                $this->getTranslationsFromPath($locale, $dir);
            }
        }
    }

    protected function getTranslationsFromPath($locale, $path, $prefix = '')
    {
        if(! File::isDirectory($path)) {
            return;
        }

        foreach(glob($path . '/*.php') as $file) {
            $this->addFileToTranslations($locale, $file, $prefix);
        }

        foreach(glob($path . '/*' , GLOB_ONLYDIR) as $dir) {
            $prefix = $prefix . ($prefix ? '.' : '') . basename($dir);
            $this->addFileToTranslations($locale, $file, $prefix);
        }
    }

    protected function addFileToTranslations($locale, $file, $prefix = '')
    {
        $content = require($file);
        $prefix = $prefix . ($prefix ? '.' : '') . basename($file, ".php");
        $translations = Arr::dot([$prefix => $content]);
        $this->translations[$locale] = array_merge($this->translations[$locale], $translations);
    }
}
