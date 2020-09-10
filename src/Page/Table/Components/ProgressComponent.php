<?php

namespace Ignite\Page\Table\Components;

class ProgressComponent extends ColumnComponent
{
    public function max(int $max)
    {
        return $this->prop('max', $max);
    }

    public function variant($variant)
    {
        return $this->prop('variant', $variant);
    }

    public function showProgress(bool $show = true)
    {
        return $this->prop('show-progress', $show);
    }

    public function striped(bool $striped = true)
    {
        return $this->prop('striped', $striped);
    }

    public function animated(bool $animated = true)
    {
        return $this->prop('animated', $animated);
    }
}
