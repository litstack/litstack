<?php

namespace Fjord\Page\Table\Components;

class ImageComponent extends ColumnComponent
{
    /**
     * Prop options.
     *
     * @return array
     */
    // protected function props()
    // {
    //     $props = [
    //         'src' => [
    //             'type'     => 'string',
    //             'required' => true,
    //         ],
    //         'maxWidth' => [
    //             'type' => 'string',
    //         ],
    //         'maxHeight' => [
    //             'type' => 'string',
    //         ],
    //         'square' => [
    //             'type' => 'string',
    //         ],
    //     ];

    //     return array_merge(
    //         parent::props(),
    //         $props
    //     );
    // }

    public function src($src)
    {
        return $this->prop('src', $src);
    }

    public function maxWidth($maxWidth)
    {
        return $this->prop('maxWidth', $maxWidth);
    }

    public function maxHeight($maxHeight)
    {
        return $this->prop('maxWidth', $maxHeight);
    }

    public function square(bool $square = true)
    {
        return $this->prop('square', $square);
    }
}
