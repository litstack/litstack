<?php

namespace Fjord\Contracts\Page;

use Illuminate\Support\Collection;

interface Action
{
    public function run(Collection $models);
}
