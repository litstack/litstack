<?php

namespace Ignite\Crud\Actions;

use Ignite\Crud\Requests\CrudDeleteRequest;
use Ignite\Page\Actions\ActionModal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteParameterBinder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class DestroyAction
{
    /**
     * Create the modal.
     *
     * @param  ActionModal $model
     * @return void
     */
    public function modal(ActionModal $modal)
    {
        $modal->message(__lit('base.messages.are_you_sure'))
            ->confirmVariant('danger')
            ->confirmText(ucfirst(__lit('base.delete')));
    }

    /**
     * Run the action.
     *
     * @param  Request    $request
     * @param  Collection $models
     * @return Response
     */
    public function run(CrudDeleteRequest $request, Collection $models)
    {
        $models->each->delete();

        return $this->resolveResponse($request, $models);
    }

    /**
     * Resolve the response for the given request.
     *
     * @param  Request    $request
     * @param  Collection $models
     * @return Response
     */
    protected function resolveResponse(Request $request, Collection $models)
    {
        $route = Route::matchesUri(
            $request->headers->get('referer'), 'GET'
        );

        if (! $route) {
            return $this->successMessage($models);
        }

        if (! Str::endsWith($route->getName(), '.show')) {
            return $this->successMessage($models);
        }

        $parameters = (new RouteParameterBinder($route))
            ->parameters(new RequestMock($request->headers->get('referer')));

        $url = route(
            Str::replaceLast('show', 'index', $route->getName()),
            $parameters
        );

        // Redirect to index if referer route is "show" of the crud.
        return redirect(explode('?', $url)[0]);
    }

    /**
     * Creates success response.
     *
     * @param  Collection   $models
     * @return JsonResponse
     */
    protected function successMessage(Collection $models)
    {
        return response()->success(__lit_choice('crud.messages.deleted_items', count($models)));
    }
}
