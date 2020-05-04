<?php

namespace Fjord\Vue;



class TableComponent extends Component
{
    /**
     * Prop options.
     *
     * @return array
     */
    protected function props()
    {
        return [
            'link' => [
                'type' => ['boolean', 'string'],
                'default' => false,
            ],
            'small' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'sortBy' => [
                'type' => 'string',
                'default' => 'id.desc'
            ],
            'label' => [
                'type' => 'string',
                'default' => ''
            ],
            'value' => [
                'type' => ['string', 'integer'],
            ]
        ];
    }

    /**
     * Get array.
     *
     * @return array
     */
    public function getArray(): array
    {
        $array = parent::getArray();
        unset($array['name']);
        $array['component'] = $this->name;
        return $array;
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
