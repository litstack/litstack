<?php

namespace Lit\Exceptions;

use Exception;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use Lit\Exceptions\Solutions\LitSolution;
use Lit\Exceptions\Solutions\InstallLitSolution;

class MissingLitEnvironmentException extends Exception implements ProvidesSolution
{
    public function getSolution(): Solution
    {
        if (lit()->needsDumpAutoload()) {
            return LitSolution::create('Missing Lit package cache.')
                ->setSolutionDescription('Call `composer dumpautoload`.')
                ->withoutDocs();
        }

        return new InstallLitSolution();
    }
}
