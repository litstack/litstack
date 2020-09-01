<?php

namespace Ignite\Contracts\Foundation;

interface Litstack
{
    /**
     * Determines if the application is translatable.
     *
     * @return bool
     */
    public function isAppTranslatable();

    /**
     * Get Lit route by name.
     *
     * @param  string $name
     * @return string
     */
    public function route(string $name);

    /**
     * Gets the  authenticated Lit user.
     *
     * @return \Lit\Models\User|null
     */
    public function user();

    /**
     * Add css file to the application.
     *
     * @param  string $path
     * @return void
     */
    public function style($path);

    /**
     * Add script to the application.
     *
     * @param  string $src
     * @return void
     */
    public function script($src);

    /**
     * Get litstack application namesapce.
     *
     * @return string
     */
    public function getNamespace();

    /**
     * Get translation for Lit application.
     *
     * @param  string      $key
     * @param  array       $replace
     * @param  string|null $locale
     * @return string
     */
    public function trans(string $key = null, $replace = [], $locale = null);

    /**
     * Get choice translation for Lit application.
     *
     * @param  string      $key
     * @param  int         $number
     * @param  array       $replace
     * @param  string|null $locale
     * @return string
     */
    public function transChoice(string $key = null, $number, $replace = [], $locale = null);

    /**
     * Get locale for Lit application.
     *
     * @return void
     */
    public function getLocale();

    /**
     * Get translation for Lit application.
     *
     * @param  string      $key
     * @param  array       $replace
     * @param  string|null $locale
     * @return string
     */
    public function __(string $key = null, $replace = [], $locale = null);

    /**
     * Get Lit application.
     *
     * @return \Ignite\Application\Application $app
     */
    public function app();
}
