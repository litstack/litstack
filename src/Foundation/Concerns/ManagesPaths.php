<?php

namespace Ignite\Foundation\Concerns;

trait ManagesPaths
{
    /**
     * The base path of the "lit" folder.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Path to the litstack package vendor folder.
     *
     * @var string
     */
    protected $vendorPath;

    /**
     * Set the base path for the application.
     *
     * @param  string $basePath
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');

        $this->bindPathsInContainer();

        return $this;
    }

    /**
     * Set path to the package vendor.
     *
     * @param  string $vendorPath
     * @return void
     */
    public function setVendorPath($vendorPath)
    {
        $this->vendorPath = rtrim($vendorPath, '\/');

        $this->laravel->instance('lit.path.vendor', $this->vendorPath);
    }

    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
        $this->laravel->instance('lit.path', $this->path());
        $this->laravel->instance('lit.path.base', $this->basePath());
        $this->laravel->instance('lit.path.lang', $this->langPath());
        $this->laravel->instance('lit.path.resources', $this->resourcePath());
    }

    /**
     * Get the path to the litstack "app" directory.
     *
     * @param  string $path
     * @return string
     */
    public function path($path = '')
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'app'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Get the base path of the "lit" folder.
     *
     * @param  string $path
     * @return string
     */
    public function basePath($path = '')
    {
        return $this->basePath.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Get the path to the Litstack package language files.
     *
     * @return string
     */
    public function langPath()
    {
        return $this->resourcePath().DIRECTORY_SEPARATOR.'lang';
    }

    /**
     * Get the path to the Litstack resources directory.
     *
     * @param  string $path
     * @return string
     */
    public function resourcePath($path = '')
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'resources'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Get the path to the litstack package vendor folder.
     *
     * @param  string $path
     * @return string
     */
    public function vendorPath($path = '')
    {
        return $this->vendorPath.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
