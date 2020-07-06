<?php

namespace Fjord\Exceptions;

use Exception;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use Fjord\Exceptions\Solutions\FjordSolution;
use Fjord\Exceptions\Solutions\InstallFjordSolution;

class MissingFjordEnvironmentException extends Exception implements ProvidesSolution
{
    public function getSolution(): Solution
    {
        if (fjord()->needsDumpAutoload()) {
            return FjordSolution::create('Missing Fjord package cache.')
                ->setSolutionDescription('Call `composer dumpautoload`.')
                ->withoutDocs();
        }

        return new InstallFjordSolution();
    }
}
