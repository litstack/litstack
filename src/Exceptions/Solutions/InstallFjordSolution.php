<?php

namespace Fjord\Exceptions\Solutions;

use Facade\IgnitionContracts\RunnableSolution;
use Illuminate\Support\Facades\Artisan;

class InstallFjordSolution implements RunnableSolution
{
    public function getSolutionTitle(): string
    {
        return 'Fjord is not installed';
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Fjord Installation' => 'https://www.fjord-admin.com/docs/getting-started/installation/',
        ];
    }

    public function getSolutionActionDescription(): string
    {
        return 'Install fjord using `php artisan fjord:install`.';
    }

    public function getRunButtonText(): string
    {
        return 'Install Fjord';
    }

    public function getSolutionDescription(): string
    {
        return '';
    }

    public function getRunParameters(): array
    {
        return [];
    }

    public function run(array $parameters = [])
    {
        Artisan::call('fjord:install');
    }
}
