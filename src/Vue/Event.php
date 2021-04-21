<?php

namespace Ignite\Vue;

use Ignite\Support\HasAttributes;
use Ignite\Support\VueProp;

class Event extends VueProp
{
    use HasAttributes;

    /**
     * Event name.
     *
     * @var string
     */
    protected $name;

    /**
     * Event handle class.
     *
     * @var string
     */
    protected $handler;

    /**
     * Create new Event instance.
     *
     * @param  string $event
     * @param  string $handler
     * @return void
     */
    public function __construct($name, $handler)
    {
        $this->name = $name;
        $this->handler = $handler;
    }

    /**
     * Determine wether the event is a file download.
     *
     * @param  bool $isDownload
     * @return bool
     */
    public function isFileDownload(bool $isDownload = true)
    {
        $this->setAttribute('isFileDownload', $isDownload);

        return $this;
    }

    /**
     * Get the event handler class.
     *
     * @return string
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Get the event name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Render the event.
     *
     * @return array
     */
    public function render(): array
    {
        return array_merge($this->attributes, [
            'name'    => $this->name,
            'handler' => $this->handler,
        ]);
    }
}
