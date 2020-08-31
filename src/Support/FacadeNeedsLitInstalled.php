<?php

namespace Ignite\Support;

use Ignite\Exceptions\MissingLitEnvironmentException;

trait FacadeNeedsLitInstalled
{
    /**
     * Resolve the facade root instance from the container.
     *
     * @param  object|string $name
     * @return mixed
     */
    protected static function resolveFacadeInstance($name)
    {
        if (! lit()->installed()) {
            throw new MissingLitEnvironmentException('The '.static::class.' requires lit to be installed.');
        }

        return parent::resolveFacadeInstance($name);
    }
}
