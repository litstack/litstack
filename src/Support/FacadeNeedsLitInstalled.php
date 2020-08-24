<?php

namespace Lit\Support;

use Lit\Exceptions\MissingLitEnvironmentException;

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
        if (lit()->needsDumpAutoload()) {
            throw new MissingLitEnvironmentException('Missing lit packages cache. Try [composer dumpautoload].');
        }
        if (! lit()->installed()) {
            throw new MissingLitEnvironmentException('The '.static::class.' requires lit to be installed.');
        }

        return parent::resolveFacadeInstance($name);
    }
}
