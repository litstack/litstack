<?php

namespace AwStudio\Fjord\Fjord\Extend;

use AwStudio\Fjord\Support\Facades\FjordRoute;
use Illuminate\Routing\Route;

class Package
{
    protected $name;

    protected $providers = [];

    protected $extensions = [];

    protected $extensionComposer = [];

    public function __construct($name, $config = [])
    {
        $this->name = $name;
        $this->providers = $config['providers'] ?? [];
    }

    public function getRoutePrefix()
    {
        return config('fjord.route_prefix') . "/" . $this->name;
    }

    public function getRouteAs()
    {
        return "fjord." . str_replace('/', '.', $this->name) . ".";
    }

    public function getName()
    {
        return $this->name;
    }

    public function getProviders()
    {
        return $this->providers;
    }

    public function getExtensions()
    {
        return $this->extensions;
    }

    public function route($name = null)
    {
        if(is_string($name) && $name != "") {
            return route($this->getRouteAs() . $name);
        }

        return FjordRoute::package($this);
    }

    public function extendable($route, $pattern = [])
    {
        $name = $route;
        if($route instanceof Route) {
            $name = $route->getName();
        }
        $name = str_replace("fjord." . str_replace("/" , ".", $this->name) . ".", "", $name);
        $this->extensions[$name] = new PackageExtension($name, $this->name, $pattern);
    }


    public function extension($name)
    {
        return $this->extensions[$name];
    }
}
