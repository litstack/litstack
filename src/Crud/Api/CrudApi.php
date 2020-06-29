<?php

namespace Fjord\Crud\Api;

use Illuminate\Http\Request;
use TypeError;

class CrudApi
{
    /**
     * ApiRepositories instance.
     *
     * @var ApiRepositories
     */
    protected $repositories;

    /**
     * Create new CrudApi request.
     *
     * @param ApiRepositories $repositories
     */
    public function __construct(ApiRepositories $repositories)
    {
        $this->repositories = $repositories;
    }

    /**
     * Handle api request.
     *
     * @param Request $request
     * @param [type] $controller
     * @return void
     */
    public function handle(Request $request, $controller)
    {
        $repository = $this->repositories->findOrFail($request->route('action') ?? 'default');
        $loader = new ApiLoader($controller, $controller->getConfig());

        $repositoryInstance = $this->makeRepository($request, $repository, $loader, $controller);
        $model = $request->id ? $loader->loadModelOrFail($request->id) : null;

        $type = $this->getType($request);

        if (!method_exists($repositoryInstance, $type)) {
            abort(404, debug("Method [{$type}] does not exist on " . get_class($repositoryInstance)));
        }

        return app()->call([$repositoryInstance, $this->getType($request)], [
            'model' => $model,
            'payload' => (object) ($request->payload ?: [])
        ]);
    }

    /**
     * Get api type.
     *
     * @param Request $request
     * @return string
     */
    protected function getType(Request $request)
    {
        if ($type = $request->route('type')) {
            return $type;
        }

        return [
            'GET' => 'index',
            'POST' => 'store',
            'PUT' => 'update',
            'DELETE' => 'destroy',
        ][$request->getMethod()] ?? null;
    }

    /**
     * Make repository.
     *
     * @param Request $request
     * @param string $repository
     * @param ApiLoader $loader
     * @param [type] $controller
     * @return void
     */
    protected function makeRepository(Request $request, string $repository, ApiLoader $loader, $controller)
    {
        $field = null;
        $form = $loader->loadFormOrFail($request->route('form_type') ?? 'show');

        if ($request->field_id) {
            $field = $loader->loadFieldOrFail($form, $request->field_id);
        }

        try {
            return app()->make($repository, [
                'config' => $controller->getConfig(),
                'controller' => $controller,
                'field' => $field,
                'form' => $form,
            ]);
        } catch (TypeError $e) {
            abort(404, debug($e->getMessage()));
        }
    }
}
