<?php

namespace Fjord\Crud\Actions;

use Illuminate\Support\Collection;

class DestroyAction
{
    public function run(Collection $models)
    {
        $models->map(fn ($item) => $item->delete());

        return success(
            __f_choice('messages.deleted_items', count($models))
        );
    }
}
