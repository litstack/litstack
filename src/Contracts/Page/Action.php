<?php

namespace Ignite\Contracts\Page;

use Illuminate\Support\Collection;

interface Action
{
    /**
     * Run the action for the given models.
     *
     * @param  Collection $models
     * @return mixed
     */
    public function run(Collection $models);
}
