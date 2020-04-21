<?php

namespace Fjord\User\Components;

use Closure;
use Fjord\Vue\Component;

class UsersComponent extends Component
{
    /**
     * Add to recordActions prop.
     *
     * @param \Fjord\Vue\Component $component
     * @return void
     */
    public function addRecordAction(Component $component)
    {
        $this->props['config']['recordActions'][] = $component;
    }

    /**
     * Add to globalActions prop.
     *
     * @param \Fjord\Vue\Component $component
     * @return void
     */
    public function addGlobalAction(Component $component)
    {
        $this->props['config']['globalActions'][] = $component;
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
