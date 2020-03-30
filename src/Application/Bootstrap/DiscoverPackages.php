<?php

namespace AwStudio\Fjord\Application\Bootstrap;

use Illuminate\Support\Facades\File;
use AwStudio\Fjord\Application\Application;
use AwStudio\Fjord\Application\Package\FjordPackage;
use AwStudio\Fjord\Application\Package\Packages;

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
     * @param AwStudio\Fjord\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $packages = $this->discover();

        $app->bind('packages', new Packages($packages));
    }

    /**
     * Discovers all packages that are defined in the extra 
     * attribute in the composer files.
     * 
     * @return array $packages
     */
    protected function discover()
    {
        if(! File::exists($this->path)) {
            return;
        }

        $manifest = require $this->path;

        $packages = [];

        foreach($manifest as $name => $config) {
            if(! array_key_exists('package', $config)) {
                continue;
            }

            $packages[$name] = new $config['package']($name, $config);
        }

        return $packages;
    }
}