<?php

namespace Fjord\Crud\Models;

use Fjord\EloquentJs\CanEloquentJs;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as LaravelModel;

class Model extends LaravelModel
{
    use CanEloquentJs, Traits\HasConfig;

    /**
     * Get config key.
     *
     * @return string $key
     */
    public function getConfigKey()
    {
        return "crud." . Str::snake(last(explode('\\', static::class)));
    }

    /**
     * Get fields for model.
     *
     * @return array
     */
    public function getFieldsAttribute()
    {
        return collect($this->config->form->getRegisteredFields())->toArray();
    }
}
