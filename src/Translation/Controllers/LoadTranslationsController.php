<?php

namespace Ignite\Translation\Controllers;

use Ignite\Translation\i18nGenerator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class LoadTranslationsController extends Controller
{
    /**
     * Translations.
     *
     * @var array
     */
    protected $translations = [];

    /**
     * Create new TranslationController instance.
     *
     * @return void
     */
    public function __construct()
    {
        foreach (config('lit.translatable.locales') as $locale) {
            $this->translations[$locale] = [];
        }
    }

    /**
     * lang.js file for i18n translations in Lit application.
     *
     * @return response
     */
    public function i18n()
    {
        $this->loadTranslations();

        $locale = lit()->getLocale();

        $translations = [$locale => []];
        if (array_key_exists($locale, $this->translations)) {
            $translations[$locale] = $this->translations[$locale];
        }

        $converted = i18nGenerator::convert($translations);

        $js = 'window.i18n_m = '.json_encode($converted).';';

        return response($js, 200)
            ->header('Content-Type', 'text/javascript');
    }

    /**
     * Load translations from registered paths for Lit application
     * and merge simmilar groups.
     *
     * @return void
     */
    protected function loadTranslations()
    {
        foreach (app('lit.translator')->getPaths() as $path) {
            foreach (config('lit.translatable.locales') as $locale) {
                $dir = realpath($path.DIRECTORY_SEPARATOR.$locale);
                $this->getTranslationsFromPath($locale, $dir);
            }
        }
    }

    /**
     * Get translations from path.
     *
     * @param  string $locale
     * @param  string $path
     * @param  string $prefix
     * @return void
     */
    protected function getTranslationsFromPath(string $locale, string $path, string $prefix = '')
    {
        if (! File::isDirectory($path)) {
            return;
        }

        foreach (glob($path.'/*.php') as $file) {
            $this->addFileToTranslations($locale, $file, $prefix);
        }

        foreach (glob($path.'/*', GLOB_ONLYDIR) as $dir) {
            $prefix = $prefix.($prefix ? '.' : '').basename($dir);
            $this->addFileToTranslations($locale, $file, $prefix);
        }
    }

    /**
     * Add file to translations.
     *
     * @param  string $locale
     * @param  string $file
     * @param  string $prefix
     * @return void
     */
    protected function addFileToTranslations(string $locale, string $file, string $prefix = '')
    {
        $content = require $file;
        $prefix = $prefix.($prefix ? '.' : '').basename($file, '.php');
        $translations = Arr::dot([$prefix => $content]);
        $this->translations[$locale] = array_merge($this->translations[$locale], $translations);
    }
}
