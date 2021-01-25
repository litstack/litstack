<?php

namespace Ignite\Crud\Config\Factories;

use Closure;
use Ignite\Config\ConfigFactory;
use Ignite\Config\ConfigHandler;
use Ignite\Crud\Actions\DestroyAction;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudShow;
use ReflectionMethod;

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
        if (empty($parameters = $method->getParameters())) {
            return false;
        }

        $parameter = $parameters[0];

        if (! $type = $parameter->getType()) {
            return false;
        }

        $type = $type->getName();

        if ($type == CrudShow::class || is_subclass_of($type, CrudShow::class)) {
            return 'show';
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

        $page->breadcrumb($this->getBreadcrumb($config));
        // if ($config->has('index')) {
        //     $page->goBack($config->names['plural'], $config->route_prefix);
        // }

        $page->title($config->names['singular'] ?? '');

        $method($page);

        return $page;
    }
}
