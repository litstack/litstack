<?php

namespace Fjord\Crud\Fields\Relations;

use Closure;
use Fjord\Vue\Table;
use Fjord\Support\Facades\Crud;
use Fjord\Support\Facades\Fjord;

class MorphTypeManager
{
    /**
     * Morph types.
     *
     * @var array
     */
    protected $types = [];

    /**
     * Add morph type.
     *
     * @param Closure $closure
     * @return void
     */
    public function to(string $class, Closure $closure)
    {
        $config = Crud::config($class);

        if (!$config->permissions['read']) {
            return;
        }

        $table = new Table;
        $closure($table);

        $this->types[$class] = collect([
            'name' => $config->names['singular'],
            'preview' => $table
        ]);
    }

    /**
     * Get types.
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }
}
