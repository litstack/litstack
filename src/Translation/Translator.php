<?php

namespace Ignite\Translation;

use Ignite\Application\Application;
use Illuminate\Support\Facades\Lang;

class Translator
{
    /**
     * Language paths.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * Language namespaces.
     *
     * @var array
     */
    protected $namespaces = [];

    /**
     * Get translation for Lit application.
     *
     * @param  string      $key
     * @param  array       $replace
     * @param  string|null $locale
     * @return string
     */
    public function trans(string $key = null, $replace = [], $locale = null)
    {
        if (is_null($locale)) {
            $locale = $this->getLocale();
        }

        $langKey = $this->getLangKey($key);

        if ($langKey === false) {
            return $key;
        }

        return __($langKey, $replace, $locale);
    }

    /**
     * Get choice translation for Lit application.
     *
     * @param  string               $key
     * @param  \Countable|int|array $number
     * @param  array                $replace
     * @param  string|null          $locale
     * @return string
     */
    public function choice(string $key = null, $number, $replace, $locale = null)
    {
        $langKey = $this->getLangKey($key);

        if ($langKey === false) {
            return $key;
        }

        if (is_null($locale)) {
            $locale = $this->getLocale();
        }

        return trans_choice($langKey, $number, $replace, $locale);
    }

    /**
     * Get language key.
     *
     * @param  string      $key
     * @return string|bool
     */
    protected function getLangKey(string $key = null)
    {
        foreach ($this->paths as $path) {

            // Look through all registered paths and return the translation if
            // the key is found in a path.
            $namespace = $this->getNamespaceFromPath($path);
            $langKey = "{$namespace}::{$key}";

            if (! Lang::has($langKey, $this->getLocale(), $fallback = false)) {
                continue;
            }

            return $langKey;
        }

        return false;
    }

    /**
     * Get locale for Lit application.
     *
     * @return string
     */
    public function getLocale()
    {
        $fallback = config('lit.translatable.fallback_locale');

        // Not translatable
        if (! config('lit.translatable.translatable')) {
            return $fallback;
        }

        $locale = lit_user()
            ? lit_user()->locale
            : $this->getBrowserLocale();

        // Locale not allowed.
        if (! in_array($locale, config('lit.translatable.locales'))) {
            return $fallback;
        }

        return $locale;
    }

    /**
     * Get locale from browser.
     *
     * @return void
     */
    public function getBrowserLocale()
    {
        if (! isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return config('lit.translatable.fallback_locale');
        }

        return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)
            ?? config('lit.translatable.fallback_locale');
    }

    /**
     * Check if the Lit application is running in a locale.
     *
     * @param  string $locale
     * @return bool
     */
    public function isLocale(string $locale)
    {
        return $locale == $this->getLocale();
    }

    /**
     * Get namespace from path.
     *
     * @param  string $path
     * @return string $namespace
     */
    protected function getNamespaceFromPath(string $path)
    {
        return "lit/{$path}";
    }

    /**
     * Add path.
     *
     * @param  string $path
     * @return void
     */
    public function addPath(string $path)
    {
        if (in_array($path, $this->paths)) {
            return;
        }

        $namespace = $this->getNamespaceFromPath($path);
        $this->namespaces[$namespace] = $path;

        Lang::addNamespace($namespace, $path);

        $this->paths[] = $path;
    }

    /**
     * Get paths.
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }
}
