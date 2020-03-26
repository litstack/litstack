<?php

namespace AwStudio\Fjord\Foundation\Console;

use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Console\PackageDiscoverCommand as LaravelPackageDiscoverCommand;

class PackageDiscoverCommand extends LaravelPackageDiscoverCommand
{
    protected $vendorPath;
    protected $manifestPath;
    protected $packages = [];

    public function __construct()
    {
        $this->vendorPath = base_path('vendor');
        $this->manifestPath = base_path('bootstrap/cache/fjord.php');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param  \AwStudio\Fjord\Foundation\PackageManifest  $manifest
     * @return void
     */
    public function handle(PackageManifest $manifest)
    {
        $this->build();

        foreach (array_keys($this->packages) as $package) {
            $this->line("Discovered Fjord Package: <info>{$package}</info>");
        }

        parent::handle($manifest);
    }

    public function build()
    {
        $this->packages = [];

        if (File::exists($path = $this->vendorPath.'/composer/installed.json')) {
            $packages = json_decode(File::get($path), true);
        }
        $this->packages = collect($packages)->mapWithKeys(function ($package) {
            return [$this->format($package['name']) => $package['extra']['fjord'] ?? []];
        })->filter()->all();

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
        return str_replace($this->vendorPath.'/', '', $package);
    }

    /**
     * Write the given manifest array to disk.
     *
     * @param  array  $manifest
     * @return void
     *
     * @throws \Exception
     */
    protected function write(array $manifest)
    {
        if (! is_writable(dirname($this->manifestPath))) {
            throw new Exception('The '.dirname($this->manifestPath).' directory must be present and writable.');
        }

        File::replace(
            $this->manifestPath, '<?php return '.var_export($manifest, true).';'
        );
    }
}
