<?php

namespace Ignite\Translation;

/**
 * Generator inspired by martinlindhe.
 *
 * @see https://github.com/martinlindhe/laravel-vue-i18n-generator
 */
class i18nGenerator
{
    /**
     * Escape char.
     *
     * @var string
     */
    protected $escapeChar = '!';

    /**
     * Translations array.
     *
     * @var array
     */
    protected $translations = [];

    /**
     * Create new i18nGenerator instance.
     *
     * @param  array $translations
     * @return void
     */
    public function __construct(array $translations)
    {
        $this->translations = $translations;
    }

    /**
     * Convert translations array.
     *
     * @param  array $translations
     * @return array
     */
    public static function convert(array $translations)
    {
        return (new self($translations))->get();
    }

    /**
     * Get converted translations.
     *
     * @return array
     */
    public function get()
    {
        return $this->convertTranslations();
    }

    /**
     * Convert translations.
     *
     * @return array
     */
    public function convertTranslations()
    {
        return $this->convertArray($this->translations);
    }

    /**
     * Convert nested array.
     *
     * @param  array $array
     * @return array
     */
    public function convertArray(array $array)
    {
        $converted = [];
        foreach ($array as $key => $section) {
            if (is_array($section)) {
                $converted[$key] = $this->convertArray($section);
            } elseif (is_string($section)) {
                $converted[$key] = $this->convertString($section);
            }
        }

        return $converted;
    }

    /**
     * Turn Laravel style ":link" into vue-i18n style "{link}".
     *
     * @param  string $string
     * @return string
     */
    protected function convertString(string $string)
    {
        $escapedEscapeChar = preg_quote($this->escapeChar, '/');

        return preg_replace_callback(
            "/(?<!mailto|tel|{$escapedEscapeChar}):\w+/",
            function ($matches) {
                return '{'.strtolower(mb_substr($matches[0], 1)).'}';
            },
            $string
        );
    }
}
