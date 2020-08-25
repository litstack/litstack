<?php

namespace Ignite\Exceptions;

use Exception;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use Ignite\Exceptions\Solutions\LitSolution;
use Ignite\Exceptions\Solutions\InstallLitSolution;

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
