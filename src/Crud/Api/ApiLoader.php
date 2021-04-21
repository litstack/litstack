<?php

namespace Ignite\Crud\Api;

use BadMethodCallException;
use Ignite\Config\ConfigHandler;
use Ignite\Crud\BaseCrudShow;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudBaseController;
use Ignite\Crud\CrudIndex;
use Illuminate\Support\Str;

class ApiLoader
{
    /**
     * Crud controller.
     *
     * @var CrudBaseController
     */
    protected $controller;

    /**
     * Crud config.
     *
     * @var ConfigHandler
     */
    protected $config;

    /**
     * Create new ApiLoader instance.
     *
     * @param  CrudBaseController $controller
     * @param  ConfigHandler      $config
     * @return void
     */
    public function __construct(CrudBaseController $controller, ConfigHandler $config)
    {
        $this->controller = $controller;
        $this->config = $config;
    }

    /**
     * Load form by form_type.
     *
     * @param  string        $type
     * @return BaseForm|null
     */
    public function loadForm($type)
    {
        if (! $type) {
            return;
        }

        if (! $this->config->has($type)) {
            return;
        }

        $form = $this->config->{$type};

        if ($form instanceof BaseCrudShow || $form instanceof CrudIndex) {
            $form = $form->getForm(request());
        }

        if (! $form instanceof BaseForm) {
            return false;
        }

        return $form;
    }

    /**
     * Load field from form by field_id.
     *
     * @param  BaseForm   $form
     * @param  string     $field_id
     * @return Field|null
     */
    public function loadField(BaseForm $form, $field_id)
    {
        return $form->findfield($field_id);
    }

    /**
     * Load model by id.
     *
     * @param  string|int $id
     * @return mixed
     */
    public function loadModel($id)
    {
        return $this->controller->findOrFail($id);
    }

    /**
     * Call load or fail with Http NotFoundHttpException exception.
     *
     * @param  string     $method
     * @param  parameters $parameters
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function callLoadOrFail($method, $parameters)
    {
        $qualtifiedMethod = str_replace('OrFail', '', $method);

        $result = $this->{$qualtifiedMethod}(...$parameters);

        if (! $result) {
            abort(404, debug("Result not found for [{$qualtifiedMethod}]."));
        }

        return $result;
    }

    /**
     * Call method.
     *
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters = [])
    {
        if (Str::endsWith($method, 'OrFail') && Str::startsWith($method, 'load')) {
            return $this->callLoadOrFail($method, $parameters);
        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()',
            static::class,
            $method
        ));
    }
}
