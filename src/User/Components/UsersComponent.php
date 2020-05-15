<?php

namespace Fjord\User\Components;

use Closure;
use Fjord\Vue\RootComponent;

class UsersComponent extends RootComponent
{
    protected function slots()
    {
        return [
            'indexControls' => [
                'many' => true
            ],
            'index' => [
                'many' => true
            ]
        ];
    }
    /**
     * Edit index table.
     *
     * @param \Closure $closure
     * @return void
     */
    public function index(Closure $closure)
    {
        $closure($this->props['config']['index']);
    }
}
