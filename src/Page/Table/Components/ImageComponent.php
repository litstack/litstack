<?php

namespace Ignite\Page\Table\Components;

class ImageComponent extends ColumnComponent
{
    protected $imageClasses = [];

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

    public function square($square)
    {
        return $this->prop('square', $square);
    }

    public function imageClass($class)
    {
        if (! in_array($class, $this->imageClasses)) {
            $this->imageClasses[] = $class;
        }

        return $this;
    }

    public function circle()
    {
        return $this->imageClass('rounded-circle');
    }

    public function render(): array
    {
        $this->prop('imageClasses', $this->imageClasses);

        return parent::render();
    }
}
