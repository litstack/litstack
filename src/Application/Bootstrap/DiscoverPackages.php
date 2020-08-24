<?php

namespace Lit\Application\Bootstrap;

use Lit\Application\Application;
use Lit\Application\Package\Packages;
use Illuminate\Support\Facades\File;

class DiscoverPackages
{
    /**
     * Path to the compiled manifest including all packages.
     *
     * @var string
     */
    protected $path;

    /**
     * Set the manifest path.
     *
     * @return void
     */
    public function __construct()
    {
        $this->path = base_path('bootstrap/cache/lit.php');
    }

    /**
     * Initialize Packages instance with all Lit packages
     * and bind instance to the application.
     *
     * @param  Lit\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $packages = $this->discover();

        $app->get('packages')->add($packages);
    }

    /**
     * Get all packages that are discovered by the
     * \Lit\Foundation\Console\PackageDiscoverCommand and compiled to the
     * manifest in bootstrap/cache/lit.php.
     *
     * @return array $packages
     */
    protected function discover()
    {
        if (! File::exists($this->path)) {
            return [];
        }

        $manifest = require $this->path;

        $packages = [];

        foreach ($manifest as $name => $config) {
            if (! array_key_exists('package', $config)) {
                continue;
            }

            if (! class_exists($config['package'])) {
                continue;
            }

            $packages[$name] = new $config['package']($name, $config);
        }

        return $packages;
    }
}
