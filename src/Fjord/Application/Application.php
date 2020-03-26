<?php

namespace AwStudio\Fjord\Fjord\Application;

use Illuminate\Support\Facades\Request;

class Application
{
    protected $requiredProps = [
        'component'
    ];

    protected $optionalProps = [
        'models' => [],
    ];


    protected $props = [];

    public function __construct()
    {

    }

    protected function getDefaultProps()
    {
        return [
            'config' => collect(config('fjord')),
            'auth' => fjord_user(),
            'translatable' => collect([
                'language' => app()->getLocale(),
                'languages' => collect(config('translatable.locales')),
                'fallback_locale' => config('translatable.fallback_locale'),
            ]),
        ];
    }

    public function addProp($name, $value)
    {
        $this->props[$name] = $value;
    }

    public function build($vars)
    {
        extract($vars);

        $this->props = array_merge($this->props, $this->getDefaultProps());

        foreach($this->requiredProps as $prop) {
            if(! isset($$prop)) {
                throw new \Exception("Missing variable \"{$prop}\" in fjord::app.");
            }

            $this->props[$prop] = $$prop;
        }

        $this->props['props'] = $this->buildProps($props);
        $this->props['models'] = $this->buildModels($model ?? []);
    }

    protected function buildModels($models)
    {
        $preparedModels = collect([]);
        foreach($models ?? [] as $title => $model) {
            $preparedModels[$title] = $model->toArray();
        }
        return $preparedModels;
    }

    protected function buildProps($props)
    {
        $props = collect($props ?? []);
        $extension = fjord()->getExtension(Request::route()->getName());

        if(! $extension) {
            return $props;
        }

        return $props->mergeRecursive($extension->getProps());
    }


    public function getProps()
    {
        return $this->props;
    }
}
