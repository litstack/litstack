<?php

namespace Fjord\Crud\Api;

use Closure;
use Fjord\Crud\Repositories\ListRepository;

class ApiRepositories
{
    /**
     * List of registered repository classes.
     *
     * @var array
     */
    protected $repositories = [];

    /**
     * Register new repository class.
     *
     * @param string $abstract
     * @param string $repository
     * @return void
     */
    public function register(string $abstract, string $repository)
    {
        unset($this->repositories[$abstract]);

        $this->repositories[$abstract] = $repository;
    }

    /**
     * Find repository by abstract.
     *
     * @param string $abstract
     * @return string|null
     */
    public function find($abstract)
    {
        if (isset($this->repositories[$abstract])) {
            return $this->repositories[$abstract];
        }
    }

    /**
     * Find repository by abstract or throw not foudn exception.
     *
     * @param string $abstract
     * @return void
     * 
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function findOrFail(string $abstract)
    {
        return $this->find($abstract) ?: abort(404, debug("Repository [{$abstract}] not found."));
    }
}
