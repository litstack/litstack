<?php

namespace Ignite\Crud\Config\Factories;

use Closure;
use Ignite\Config\ConfigFactory;
use Ignite\Config\ConfigHandler;
use Ignite\Contracts\Crud\CrudCreate;
use Ignite\Contracts\Crud\CrudUpdate;
use Ignite\Crud\Actions\DestroyAction;
use Ignite\Crud\BaseCrudShow;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudShow;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use ReflectionUnionType;

class CrudFormConfigFactory extends ConfigFactory
{
    use Concerns\ManagesBreadcrumb;

    /**
     * Get alias for the given method.
     *
     * @param  string      $method
     * @return bool|string
     */
    public function getAliasFor(ReflectionMethod $method)
    {
        if (! $method->isPublic()) {
            return false;
        }

        if (empty($parameters = $method->getParameters())) {
            return false;
        }

        $parameter = $parameters[0];

        if (! $type = $parameter->getType()) {
            return false;
        }

        $types = [$type];
        if ($type instanceof ReflectionUnionType) {
            $types = $type->getTypes();
        }

        foreach ($types as $type) {
            $type = $type->getName();

            if (is_subclass_of($type, CrudCreate::class) || is_subclass_of($type, CrudUpdate::class)) {
                return 'show';
            }
        }

        return false;
    }

    /**
     * Setup create and edit form.
     *
     * @param  \Ignite\Config\ConfigHandler $config
     * @param  Closure                      $method
     * @return \Ignite\Crud\CrudForm
     */
    public function show(ConfigHandler $config, Closure $method, $alias)
    {
        $form = new BaseForm($config->model);

        $form->setRoutePrefix(
            strip_slashes($config->routePrefix().'/{id}/api/'.$alias)
        );

        $pageClass = $this->resolvePageClass($config, $alias);
        $page = new $pageClass($config, $form);

        if ($config->is(CrudConfig::class)) {
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
     * Get the page class for the given alias.
     *
     * @param  ConfigHandler $config
     * @param  string        $method
     * @return string
     */
    protected function resolvePageClass(ConfigHandler $config, string $method = 'show')
    {
        if ($config->methodNeeds($method, CrudCreate::class, $pos = 0) &&
            $config->methodNeeds($method, CrudUpdate::class, $pos = 0)) {
            return \Ignite\Crud\CrudShow::class;
        }

        if ($config->methodNeeds($method, CrudCreate::class, $pos = 0)) {
            return \Ignite\Crud\CrudCreate::class;
        }

        return \Ignite\Crud\CrudUpdate::class;
    }

    /**
     * Bind events from config.
     *
     * @param  BaseCrudShow $config
     * @param  CrudShow     $page
     * @return void
     */
    protected function bindEventsFromConfig(ConfigHandler $config, BaseCrudShow $page)
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
