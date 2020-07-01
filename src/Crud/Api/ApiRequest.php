<?php

namespace Fjord\Crud\Api;

use Closure;
use Fjord\Crud\BaseForm;
use Fjord\Crud\Controllers\CrudBaseController;
use Fjord\Crud\Field;
use Fjord\Crud\Models\Traits\TrackEdits;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use TypeError;

class ApiRequest
{
    /**
     * ApiRepositories instance.
     *
     * @var ApiRepositories
     */
    protected $repositories;

    /**
     * Crud controller.
     *
     * @var CrudBaseController
     */
    protected $controller;

    /**
     * Http request.
     *
     * @var Request
     */
    protected $request;

    /**
     * Repository abstract.
     *
     * @var string
     */
    protected $abstract;

    /**
     * Child repository abstract.
     *
     * @var string
     */
    protected $childAbstract;

    /**
     * Repository class.
     *
     * @var string
     */
    protected $repositoryClass;

    /**
     * Repository method.
     *
     * @var string
     */
    protected $method;

    /**
     * Create new CrudApi request.
     *
     * @param ApiRepositories    $repositories
     * @param Request            $request
     * @param ApiLoader          $loader
     * @param CrudBaseController $controller
     *
     * @return void
     */
    public function __construct(ApiRepositories $repositories, Request $request, ApiLoader $loader, CrudBaseController $controller)
    {
        $this->repositories = $repositories;
        $this->request = $request;
        $this->controller = $controller;
        $this->loader = $loader;

        $this->setAbstracts();
        $this->setMethod();
    }

    /**
     * Handle api request.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return mixed
     */
    public function handle()
    {
        $repository = $this->getRepository();
        $model = $this->getModel();

        if ($this->hasChild()) {
            $model = $this->getParentModel($repository, $model);
            $repository = $this->getChildRepository($repository);
        }

        if (! method_exists($repository, $this->method)) {
            abort(404, debug("Method [{$this->method}] does not exist on ".get_class($repository)));
        }

        $inject = ['payload' => (object) ($this->request->payload ?: [])];

        if ($model) {
            $inject['model'] = $model;
        }

        try {
            $response = app()->call([$repository, $this->method], $inject);
        } catch (BindingResolutionException $e) {
            abort(404, $e->getMessage());
        }

        $this->storeEdit();

        return $response;
    }

    /**
     * Store model edit.
     *
     * @return void
     */
    protected function storeEdit()
    {
        if (in_array($this->method, ['load', 'index'])) {
            return;
        }

        $model = $this->getModel();

        if (! $model) {
            return;
        }

        if (! in_array(TrackEdits::class, class_uses_recursive($model))) {
            return;
        }

        $abstract = $this->hasChild() ? $this->childAbstract : $this->abstract;

        $model->edited("{$abstract}:{$this->method}");
    }

    /**
     * Get model.
     *
     * @return mixed
     */
    protected function getModel()
    {
        if (! $id = $this->request->id) {
            return;
        }

        return $this->loader->loadModelOrFail($id);
    }

    /**
     * Get child model.
     *
     * @param mixed $parentRepository
     * @param mixed $model
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return mixed
     */
    public function getParentModel($parentRepository, $model)
    {
        if (! method_exists($parentRepository, 'getModel')) {
            abort(404, debug('Missing [getModel] method on '.get_class($parentRepository)));
        }

        $model = app()->call([$parentRepository, 'getModel'], [
            'model' => $model,
        ]);

        if (! $model) {
            abort(404, debug("Couldn't find child [$this->abstract] Model for ".get_class($model)." with id [$model->id]."));
        }

        return $model;
    }

    /**
     * Get repository instance.
     *
     * @return mixed
     */
    protected function getRepository()
    {
        return $this->makeRepository(
            $this->repositories->findOrFail($this->abstract),
            $this->getFieldGetter()
        );
    }

    /**
     * Get child repository instance.
     *
     * @param mixed $parentRepository
     *
     * @return mixed
     */
    protected function getChildRepository($parentRepository)
    {
        return $this->makeRepository(
            $this->repositories->findOrFail($this->childAbstract),
            $this->getChildFieldGetter($parentRepository)
        );
    }

    /**
     * Get child field getter.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return Closure
     */
    protected function getChildFieldGetter($parentRepository)
    {
        return function () use ($parentRepository) {
            $field_id = $this->request->child_field_id;

            if (! method_exists($parentRepository, 'getField') && $field_id) {
                abort(404, debug('Missing [getField] method on '.get_class($parentRepository)));
            }

            // Getting field from parentRepository's getField method.
            $field = app()->call([$parentRepository, 'getField'], [
                'field_id' => $field_id,
            ]);

            return $this->passFieldOrFail($field_id, $field);
        };
    }

    /**
     * Field getter.
     *
     * @return Closure
     */
    protected function getFieldGetter()
    {
        $field_id = $this->request->field_id;

        return fn () => $this->passFieldOrFail(
            $field_id,
            $this->getField($field_id)
        );
    }

    /**
     * Make repository instance with bindings.
     *
     * @param string  $repository
     * @param Closure $fieldGetter
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return mixed
     */
    protected function makeRepository(string $repository, Closure $fieldGetter)
    {
        try {
            return app()->make($repository, [
                'config'     => $this->controller->getConfig(),
                'controller' => $this->controller,
                'field'      => $fieldGetter(),
                'form'       => $this->getForm(),
            ]);
        } catch (TypeError $e) {
            abort(404, debug($e->getMessage()));
        }
    }

    /**
     * Get form instance.
     *
     * @return BaseForm|null
     */
    public function getForm()
    {
        return $this->loader->loadForm($this->request->route('form_type') ?? 'show');
    }

    /**
     * Get field instance.
     *
     * @param string|null $field_id
     *
     * @return Field|null
     */
    public function getField($field_id)
    {
        if (! $form = $this->getForm()) {
            return;
        }

        return $this->loader->loadField($form, $field_id);
    }

    /**
     * Pass field id or throw Http NotFoundException.
     *
     * @param string     $field_id
     * @param Field|null $field
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return Field
     */
    protected function passFieldOrFail($field_id, $field)
    {
        if ($field_id && ! $field) {
            abort(404, debug("Couln't find field [{$field_id}]."));
        }

        return $field;
    }

    /**
     * Set abstract.
     *
     * @return void
     */
    protected function setAbstracts()
    {
        if ($this->hasChild()) {
            $this->childAbstract = $this->request->route('method');
        }

        $this->abstract = $this->request->route('repository') ?? 'default';
    }

    /**
     * Set method.
     *
     * @return void
     */
    protected function setMethod()
    {
        $this->method = $this->getMethodFromRequest($this->request);
    }

    /**
     * Get repository method from request.
     *
     * @param Request $request
     *
     * @return string
     */
    protected function getMethodFromRequest(Request $request)
    {
        if ($method = $this->request->route($this->getMethodRouteParameter())) {
            return $method;
        }

        // Get repository method from request method.
        return [
            'GET'    => 'index',
            'POST'   => 'store',
            'PUT'    => 'update',
            'DELETE' => 'destroy',
        ][$this->request->getMethod()] ?? null;
    }

    /**
     * Get method route parameter name.
     *
     * @return string
     */
    protected function getMethodRouteParameter()
    {
        if ($this->hasChild()) {
            return 'child_method';
        }

        return 'method';
    }

    /**
     * Determines if api has child repository. For example a media field in a block.
     *
     * @return bool
     */
    protected function hasChild()
    {
        return ! empty($this->request->child_field_id);
    }
}
