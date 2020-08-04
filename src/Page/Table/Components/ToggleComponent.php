<?php

namespace Fjord\Page\Table\Components;

class ToggleComponent extends ColumnComponent
{
    public function routePrefix($routePrefix)
    {
        return $this->prop('routePrefix', $routePrefix);
    }
}
