<?php

namespace Fjord\Vue\Components;

use Fjord\Contracts\Vue\Resizable;
use Fjord\Vue\Component;
use Fjord\Vue\Traits\CanBeResized;

class InfoComponent extends Component implements Resizable
{
    use CanBeResized;

    public function beforeMount()
    {
        $this->props['text'] = collect([]);
        $this->width(12);
        $this->heading('h4');
    }

    public function title($title)
    {
        return $this->prop('title', $title);
    }

    public function heading($heading)
    {
        return $this->prop('heading', $heading);
    }

    /**
     * Add text.
     *
     * @param  string $name
     * @return $this
     */
    public function text(string $text)
    {
        $this->props['text'][] = $text;

        return $this;
    }
}
