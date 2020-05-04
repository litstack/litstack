<?php

namespace Fjord\Vue;

class RootComponent extends Component
{
    /**
     * Available slots.
     *
     * @return array
     */
    protected function slots()
    {
        return [
            'navControls' => [
                'many' => true
            ],
            'headerControls' => [
                'many' => true
            ],
        ];
    }

    /**
     * Set defaults.
     *
     * @return void
     */
    protected function setDefault()
    {
        parent::setDefaults();
    }
}
