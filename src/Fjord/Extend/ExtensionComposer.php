<?php

namespace AwStudio\Fjord\Fjord\Extend;

use Illuminate\Support\Facades\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Fjord\Kernel;

class ExtensionComposer
{
    public function compose(View $view)
    {
        $package = $this->getCurrentPackage();

        $kernel = new Kernel();

        $classes = array_merge(
            fjord()->getExtensionComposer(),
            $kernel->extensions
        );

        foreach(collect($classes)->unique() as $class) {
            foreach($class::FOR as $extensionPackage => $route) {
                if($this->checkComposer($extensionPackage, $route, $package)) {
                    with(new $class)->extend(fjord()->app());
                }
            }
        }
    }

    protected function checkComposer($extensionPackage, $route, $package)
    {
        if($extensionPackage == '' || $route == '') {
            return false;
        }

        $packageMatch = $extensionPackage == '*'
            || $extensionPackage == ($package ? $package->getName() : '');

        $routeMatch = $route == '*'
            || Str::endsWith(Request::route()->getName(), $route);

        return $packageMatch && $routeMatch;
    }

    protected function getCurrentPackage()
    {
        $routeName = Request::route()->getName();

        foreach(fjord()->getPackages() as $package) {
            $alias = "fjord." . str_replace("/", ".", $package->getName());
            if(Str::startsWith($routeName, $alias)) {
                return $package;
            }
        }
    }
}
