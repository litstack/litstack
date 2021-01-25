<?php

namespace Ignite\Crud\Config\Factories;

use Closure;
use Ignite\Config\ConfigFactory;
use Ignite\Config\ConfigHandler;
use Ignite\Crud\Actions\DestroyAction;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudShow;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class CrudFormConfigFactory extends ConfigFactory
{
    use Concerns\ManagesBreadcrumb;

    /**
     * Setup create and edit form.
     *
     * @param  \Ignite\Config\ConfigHandler $config
     * @param  Closure                      $method
     * @return \Ignite\Crud\CrudForm
     */
    public function show(ConfigHandler $config, Closure $method)
    {
        $form = new BaseForm($config->model);

        $form->setRoutePrefix(
            strip_slashes($config->routePrefix().'/{id}/api/show')
        );

        $page = new CrudShow($form);

        if ($config->instanceOf(CrudConfig::class)) {
            $page->navigationControls()->action(ucfirst(__lit('base.delete')), DestroyAction::class);
        }

        if (is_translatable($config->model)) {
            $page->navigationRight()->component('lit-crud-language');
        }
        // dump($this->getBreadcrumb($config));
        $page->breadcrumb($this->getBreadcrumb($config));
        // if ($config->has('index')) {
        //     $page->goBack($config->names['plural'], $config->route_prefix);
        // }

        $page->title($config->names['singular'] ?? '');

        $this->bindEventsFromConfig($config, $page);

        $method($page);

        return $page;
    }

    /**
     * Bind events from config.
     *
     * @param  ConfigHandler $config
     * @param  CrudShow      $page
     * @return void
     */
    protected function bindEventsFromConfig(ConfigHandler $config, CrudShow $page)
    {
        $reflector = new ReflectionClass($config->getConfig());

        foreach ($reflector->getMethods() as $method) {
            if ($method->getModifiers() != ReflectionMethod::IS_PUBLIC) {
                continue;
            }

            if (! Str::startsWith($name = $method->getName(), 'on')) {
                continue;
            }

            // Event naming: onFooBar -> foo.bar
            $event = str_replace('_', '.',
                Str::replaceFirst('on_', '', Str::snake($name))
            );

            $page->on(
                $event, fn (...$parameters) => $config->{$name}(...$parameters)
            );
        }
    }
}
