<?php

namespace Ignite\Exceptions\Solutions;

use Facade\IgnitionContracts\RunnableSolution;
use Illuminate\Support\Facades\Artisan;

class InstallLitSolution implements RunnableSolution
{
    public function getSolutionTitle(): string
    {
        return 'Lit is not installed';
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Lit Installation' => 'https://www.lit-admin.com/docs/getting-started/installation/',
        ];
    }

    public function getSolutionActionDescription(): string
    {
        return 'Install lit using `php artisan lit:install`.';
    }

    public function getRunButtonText(): string
    {
        return 'Install Lit';
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
        Artisan::call('lit:install');
    }
}
