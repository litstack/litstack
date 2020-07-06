<?php

namespace Fjord\Support;

use Fjord\Exceptions\MissingFjordEnvironmentException;

trait FacadeNeedsFjordsInstalled
{
    /**
     * Resolve the facade root instance from the container.
     *
     * @param object|string $name
     *
     * @return mixed
     */
    protected static function resolveFacadeInstance($name)
    {
        if (fjord()->needsDumpAutoload()) {
            throw new MissingFjordEnvironmentException('Missing fjord packages cache. Try [composer dumpautoload].');
        }
        if (! fjord()->installed()) {
            throw new MissingFjordEnvironmentException('The '.static::class.' requires fjord to be installed.');
        }

        return parent::resolveFacadeInstance($name);
    }
}
