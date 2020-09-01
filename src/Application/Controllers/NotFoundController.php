<?php

namespace Ignite\Application\Controllers;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundController extends Controller
{
    /**
     * 404 not found.
     *
     * @return void
     * @throws NotFoundHttpException
     */
    public function __invoke()
    {
        throw new NotFoundHttpException();
    }
}
