<?php

namespace Fjord\Application\Config;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

class ConfigItem implements Arrayable, Jsonable
{
    /**
     * Attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Create new ConfigItem instance.
     */
    public function __construct()
    {
        // All properties that are output toArray must be an instance of a 
        // collection so that the components in it are also converted to an 
        // array. 
        $this->attributes = collect([]);
    }

    /**
     * Get attributes.
     *
     * @return array $attributes
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_map(function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            } elseif ($value instanceof Jsonable) {
                return json_decode($value->toJson(), true);
            } elseif ($value instanceof Arrayable) {
                return $value->toArray();
            }

            return $value;
        }, $this->toArray());
    }

    /**
     * To Json
     *
     * @param integer $options
     * @return string $json
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
