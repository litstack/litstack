<?php

namespace Fjord\Page\Table\Components;

class RelationComponent extends ColumnComponent
{
    /**
     * Prop options.
     *
     * @return array
     */
    // protected function props()
    // {
    //     $props = [
    //         'related' => [
    //             'type'     => 'string',
    //             'required' => true,
    //         ],
    //         'value' => [
    //             'type'     => 'string',
    //             'required' => true,
    //         ],
    //         'routePrefix' => [
    //             'type'     => 'string',
    //             'required' => true,
    //         ],
    //     ];

    //     return array_merge(
    //         parent::props(),
    //         $props
    //     );
    // }

    public function related($related)
    {
        return $this->prop('related', $related);
    }

    public function routePrefix($routePrefix)
    {
        return $this->prop('routePrefix', $routePrefix);
    }

    /**
     * Deny to set link.
     *
     * @param  string $link
     * @return void
     */
    public function link($link)
    {
        return $this;
    }
}
