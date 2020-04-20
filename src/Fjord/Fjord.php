<?php

namespace Fjord\Fjord;

use Fjord\Application\Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Traits\ForwardsCalls;

class Fjord
{
    use ForwardsCalls;

    /**
     * Fjord Application.
     *
     * @var \Fjord\Application\Application
     */
    protected $app;

    /**
     * Bind Fjord Application instance when Fjord is installed.
     *
     * @param \Fjord\Application\Application $app
     * @return void
     */
    public function bindApp(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get Fjord application.
     *
     * @return \Fjord\Application\Application $app
     */
    public function app()
    {
        return $this->app;
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
        if (is_null($key)) {
            return $key;
        }

        return $this->app->get('translator')->trans($key, $replace);
    }

    /**
     * Get translation for Fjord application.
     *
     * @param string $key
     * @param array $replace
     * @return string
     */
    public function __(string $key = null, $replace = [])
    {
        return $this->trans($key, $replace);
    }

    /**
     * Load config.
     *
     * @param string $key
     * @param array $params
     * @return $config
     */
    public function config($key, ...$params)
    {
        return $this->app->get('config.loader')->get($key, ...$params);
    }

    /**
     * Get authenticated Fjord user.
     *
     * @return \Fjord\Models\FjordUser $user
     */
    public function user()
    {
        return fjord_user();
    }

    /**
     * Get locale for Fjord application.
     *
     * @return void
     */
    public function getLocale()
    {
        return $this->app()->get('translator')->getLocale();
    }

    /**
     * Checks if fjord has been installed.
     * 
     * @return boolean
     */
    public function installed()
    {
        if (!config()->has('fjord')) {
            return false;
        }

        if (!File::exists(app_path('Fjord/Kernel.php'))) {
            return false;
        }

        try {
            return Schema::hasTable('form_fields');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Forward call to Fjord Application.
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, $params = [])
    {
        return $this->forwardCallTo($this->app, $method, $params);
    }
}
