<?php

namespace Fjord\User\Components;

use Fjord\Application\Vue\Component;

class UsersComponent extends Component
{
    /**
     * Add to recordActions prop.
     *
     * @param string $component
     * @return void
     */
    public function addRecordAction(string $component)
    {
        $this->props['config']['recordActions'][] = $component;
    }

    /**
     * Add to globalActions prop.
     *
     * @param string $component
     * @return void
     */
    public function addGlobalAction(string $component)
    {
        $this->props['config']['globalActions'][] = $component;
    }

    /**
     * Add table column to index table.
     *
     * @param array $col
     * @return void
     */
    public function addTableColumn(array $col)
    {
        $this->props['config']['cols'][] = $col;
    }
}
