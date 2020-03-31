<?php

namespace AwStudio\Fjord\User\Components;

use AwStudio\Fjord\Application\Vue\Component;

class UsersComponent extends Component
{
    /**
     * Pass props to extension that should be edited.
     * 
     * @return array props
     */
    public function passToExtension()
    {
        return $this->props;
    }

    /**
     * Receive props from extension and bind them to component. 
     *
     * @param array $data
     * @return void
     */
    public function receiveFromExtension($props)
    {
        $this->props = $props;
    }
}
