<?php

namespace Ignite\Support\Vue;

use Ignite\Contracts\Vue\Resizable;
use Ignite\Vue\Component;
use Ignite\Vue\Traits\CanBeResized;

class InfoComponent extends Component implements Resizable
{
    use CanBeResized;

    /**
     * Before mount lifecycle hook.
     *
     * @return void
     */
    public function beforeMount()
    {
        $this->props['text'] = collect([]);
        $this->width(12);
        $this->heading('h4');
    }

    /**
     * Sets the title.
     *
     * @param  string $title
     * @return $this
     */
    public function title($title)
    {
        return $this->prop('title', $title);
    }

    /**
     * Sets the heading.
     *
     * @param  string $heading
     * @return $this
     */
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
