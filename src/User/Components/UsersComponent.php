<?php

namespace AwStudio\Fjord\User\Components;

use AwStudio\Fjord\Application\Vue\Component;

class UsersComponent extends Component
{
    /**
     * Compare original props to extended props and do changes if needed.
     * 
     * @param array $original
     * @param array $extended
     * @return array $props
     */
    public function handleExtension(array $original, array $extended)
    {
        return $extended;
    }
}