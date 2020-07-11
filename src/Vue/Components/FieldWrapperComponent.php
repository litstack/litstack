<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\Component;

class FieldWrapperComponent extends Component
{
    /**
     * Available props.
     *
     * @return array
     */
    // protected function props()
    // {
    //     return [
    //         'wrapperComponent' => [
    //             'type'     => Component::class,
    //             'required' => true,
    //         ],
    //         'children' => [
    //             'type'     => ['array', 'object'],
    //             'required' => true,
    //             'default'  => function () {
    //                 return collect([]);
    //             },
    //         ],
    //     ];
    // }

    public function beforeMount()
    {
        $this->props['children'] = collect([]);
    }

    public function wrapperComponent(Component $component)
    {
        return $this->prop('wrapperComponent', $component);
    }

    /**
     * Add child component.
     *
     * @param  mixed $name
     * @return void
     */
    public function component($component)
    {
        $this->props['children'][] = component($component);

        return $component;
    }
}
