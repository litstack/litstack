<?php

namespace Ignite\Search;

use Ignite\Crud\Models\Media;
use Illuminate\Contracts\Support\Arrayable;

class Result implements Arrayable
{
    protected $attributes = [
        'title'       => null,
        'description' => null,
        'hint'        => null,
        'icon'        => null,
        'image'       => null,
        'route'       => null,
        'created_at'  => null,
    ];

    public function __construct(int $importance = 1)
    {
        $this->importance = $importance;
    }

    public function getImportance()
    {
        return $this->importance;
    }

    public function createdAt($createdAt)
    {
        $this->setAttribute('created_at', $createdAt);

        return $this;
    }

    public function route($route)
    {
        $this->setAttribute('route', $route);

        return $this;
    }

    public function title($title)
    {
        $this->setAttribute('title', $title);

        return $this;
    }

    public function description($description)
    {
        $this->setAttribute('description', strip_tags($description));

        return $this;
    }

    public function hint($hint)
    {
        $this->setAttribute('hint', $hint);

        return $this;
    }

    public function icon($icon)
    {
        $this->setAttribute('icon', $icon);

        return $this;
    }

    public function image($image)
    {
        if ($image instanceof Media) {
            $this->setAttribute('icon', '<img src="'.$image->url.'" style="width: 50px;height: 50px;border-radius: 5px;transform: translateX(-15px);"/>');
        } else {
            $this->setAttribute('icon', $image);
        }

        return $this;
    }

    /**
     * Set attribute value.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     */
    public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get attribute by name.
     *
     * @param  string $name
     * @return mixed
     */
    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function toArray()
    {
        return $this->getAttributes();
    }
}
