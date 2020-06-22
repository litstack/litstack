<?php

namespace Fjord\Fjord\Discover;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Foundation\Console\PackageDiscoverCommand as LaravelPackageDiscoverCommand;

class PackageDiscoverCommand extends LaravelPackageDiscoverCommand
{
    /**
     * Path to composer vendor folder.
     *
     * @var string
     */
    protected $vendorPath;

    /**
     * Path to Fjord packages manifest.
     *
     * @var string
     */
    protected $manifestPath;

    /**
     * Discovered Fjord packages.
     *
     * @var array
     */
    protected $packages = [];

    /**
     * Create new PackageDiscoverCommand instance.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem;
        $this->vendorPath = base_path('vendor');
        $this->manifestPath = base_path('bootstrap/cache/fjord.php');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param  \Fjord\Foundation\PackageManifest  $manifest
     * @return void
     */
    public function handle(PackageManifest $manifest)
    {
        $this->build();

        foreach (array_keys($this->packages) as $package) {
            $this->line("Discovered Fjord Package: <info>{$package}</info>");
        }

        // Discover Laravel packages.
        parent::handle($manifest);
    }

    /**
     * Find Fjord packages in vendor/composer/installed.json
     *
     * @param string $vendorPath
     * @return array
     */
    public function findFjordPackages(string $vendorPath)
    {
        // Load packages form vendor/composer/installed.json
        if ($this->filesystem->exists($path = $vendorPath . '/composer/installed.json')) {
            $packages = json_decode($this->filesystem->get($path), true);
        }

        return $this->filterFjordPackages($packages);
    }

    /**
     * Filter composer packages for fjord packages.
     *
     * @return array
     */
    public function filterFjordPackages(array $packages)
    {
        // Filter for packages containing "extra": {"fjord": ...}.
        return collect($packages['packages'] ?? $packages)->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['fjord'] ?? []];
        })->filter()->all();
    }

    /**
     * Build the manifest and write it to disk.
     *
     * @return void
     */
    public function build()
    {
        $this->packages = [];
        $this->packages = $this->findFjordPackages($this->vendorPath);

        $this->write($this->packages);
    }

    /**
     * Format the given package name.
     *
     * @param  string  $package
     * @return string
     */
    protected function format($package)
    {
        return str_replace($this->vendorPath . '/', '', $package);
    }

    /**
     * Write the given manifest array to disk.
     *
     * @param  array  $manifest
     * @return void
     *
     * @throws \Exception
     */
    public function write(array $manifest)
    {
        if (!is_writable(dirname($this->manifestPath))) {
            throw new Exception('The ' . dirname($this->manifestPath) . ' directory must be present and writable.');
        }

        $this->filesystem->replace(
            $this->manifestPath,
            '<?php return ' . var_export($manifest, true) . ';'
        );
    }
}
