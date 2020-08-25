<?php

namespace Ignite\Support\Macros;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

class ResponseMacros
{
    /**
     * Register the macros.
     *
     * @return void
     */
    public function register()
    {
        $success = fn (...$parameters) => $this->success(...$parameters);
        $info = fn (...$parameters) => $this->info(...$parameters);
        $warning = fn (...$parameters) => $this->warning(...$parameters);
        $danger = fn (...$parameters) => $this->danger(...$parameters);

        Response::macro('success', fn (...$parameters) => $success(...$parameters));
        Response::macro('info', fn (...$parameters) => $info(...$parameters));
        Response::macro('warning', fn (...$parameters) => $warning(...$parameters));
        Response::macro('danger', fn (...$parameters) => $danger(...$parameters));

        ResponseFactory::macro('success', fn ($message) => $success($message));
        ResponseFactory::macro('info', fn (...$parameters) => $info(...$parameters));
        ResponseFactory::macro('warning', fn (...$parameters) => $warning(...$parameters));
        ResponseFactory::macro('danger', fn (...$parameters) => $danger(...$parameters));
    }

    /**
     * Returns JsonResponse with variant "success".
     *
     * @param  string       $message
     * @param  int          $status
     * @param  array        $headers
     * @param  int          $options
     * @return JsonResponse
     */
    protected function success($message, $status = 200, array $headers = [], $options = 0)
    {
        $data = ['message' => $message, 'variant' => 'success'];

        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * Returns JsonResponse with variant "info".
     *
     * @param  string       $message
     * @param  int          $status
     * @param  array        $headers
     * @param  int          $options
     * @return JsonResponse
     */
    protected function info($message, $status = 200, array $headers = [], $options = 0)
    {
        $data = ['message' => $message, 'variant' => 'info'];

        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * Returns JsonResponse with variant "warning".
     *
     * @param  string       $message
     * @param  int          $status
     * @param  array        $headers
     * @param  int          $options
     * @return JsonResponse
     */
    protected function warning($message, $status = 200, array $headers = [], $options = 0)
    {
        $data = ['message' => $message, 'variant' => 'warning'];

        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * Returns JsonResponse with variant "danger".
     *
     * @param  string       $message
     * @param  int          $status
     * @param  array        $headers
     * @param  int          $options
     * @return JsonResponse
     */
    protected function danger($message, $status = 405, array $headers = [], $options = 0)
    {
        $data = ['message' => $message, 'variant' => 'danger'];

        return new JsonResponse($data, $status, $headers, $options);
    }
}
