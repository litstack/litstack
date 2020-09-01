<?php

namespace Ignite\Support;

/**
 * @see https://getbootstrap.com
 */
class Bootstrap
{
    public const PRIMARY = 'primary';
    public const SECONDARY = 'secondary';
    public const SUCCESS = 'success';
    public const WARNING = 'warning';
    public const DANGER = 'danger';
    public const INFO = 'info';
    public const LIGHT = 'light';
    public const DARK = 'dark';

    /**
     * Get available bootstrap variants.
     *
     * @return array
     */
    public static function variants()
    {
        return [
            self::PRIMARY,
            self::SECONDARY,
            self::SUCCESS,
            self::WARNING,
            self::DANGER,
            self::INFO,
            self::LIGHT,
            self::DARK,
        ];
    }

    /**
     * Make bootstrap badge.
     *
     * @param  string $content
     * @param  string $variant
     * @return string
     */
    public static function badge($content, $variant = 'succcess')
    {
        return '<div class="badge badge-'.$variant.'">'.$content.'</div>';
    }
}
