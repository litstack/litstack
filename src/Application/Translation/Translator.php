<?php

namespace Fjord\Application\Translation;

use Illuminate\Support\Facades\Lang;
use Fjord\Application\Application;

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
     * Fjord application instance.
     *
     * @var Fjord\Application\Application
     */
    protected $app;

    /**
     * Create new Translator instance.
     *
     * @param Fjord\Application\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
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
        foreach ($this->paths as $path) {

            // Look through all registered paths and return the translation if 
            // the key is found in a path.
            $namespace = $this->getNamespaceFromPath($path);
            $langKey = "{$namespace}::{$key}";

            if (!Lang::has($langKey)) {
                continue;
            }

            return __($langKey, $replace, $this->getLocale());
        }

        return $key;
    }

    /**
     * Get locale for Fjord application.
     *
     * @return string $locale
     */
    public function getLocale()
    {
        $fallback = config('fjord.translatable.fallback_locale');

        // Not translatable
        if (!config('fjord.translatable.translatable')) {
            return $fallback;
        }

        // Not logged in.
        if (!fjord_user()) {
            return $fallback;
        }

        // Locale not allowed.
        if (!in_array(fjord_user()->locale, config('fjord.translatable.locales'))) {
            return $fallback;
        }

        return fjord_user()->locale;
    }

    /**
     * Get namespace from path.
     *
     * @param string $path
     * @return string $namespace
     */
    protected function getNamespaceFromPath(string $path)
    {
        return "fjord/{$path}";
    }

    /**
     * Add path.
     *
     * @param string $path
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
