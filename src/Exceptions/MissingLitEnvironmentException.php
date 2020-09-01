<?php

namespace Ignite\Exceptions;

use Exception;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use Ignite\Exceptions\Solutions\InstallLitSolution;

class MissingLitEnvironmentException extends Exception implements ProvidesSolution
{
    public function getSolution(): Solution
    {
        return new InstallLitSolution();
    }
}
