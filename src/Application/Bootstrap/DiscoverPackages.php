<?php

namespace Fjord\Application\Bootstrap;

use Fjord\Application\Application;
use Fjord\Application\Package\Packages;
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
     */
    public function __construct()
    {
        $this->path = base_path('bootstrap/cache/fjord.php');
    }

    /**
     * Initialize Packages instance with all Fjord packages
     * and bind instance to the application.
     *
     * @param Fjord\Application\Application $app
     *
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $packages = $this->discover();

        $app->get('packages')->add($packages);
    }

    /**
     * Get all packages that are discovered by the
     * \Fjord\Foundation\Console\PackageDiscoverCommand and compiled to the
     * manifest in bootstrap/cache/fjord.php.
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

            $packages[$name] = new $config['package']($name, $config);
        }

        return $packages;
    }
}
