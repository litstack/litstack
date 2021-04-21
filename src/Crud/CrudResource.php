<?php

namespace Ignite\Crud;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class CrudResource extends JsonResource
{
    /**
     * The config instance.
     *
     * @var ConfigHandler|Field
     */
    protected $config;

    /**
     * Create a new resource instance.
     *
     * @param  mixed               $resource
     * @param  ConfigHandler|Field $config
     * @return void
     */
    public function __construct($resource, $config)
    {
        parent::__construct($resource);
        $this->config = $config;
    }

    /**
     * Render the resource.
     *
     * @param  Request $request
     * @return array
     */
    public function render($request)
    {
        return array_merge(
            $this->toArray($request),
            $this->getCrudArray()
        );
    }

    /**
     * Get crud array.
     *
     * @return array
     */
    protected function getCrudArray()
    {
        $array = [];

        if ($this->_lit_route) {
            $array['_lit_route'] = $this->_lit_route;
        }

        return $array;
    }
}
