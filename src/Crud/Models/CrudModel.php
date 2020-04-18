<?php

namespace Fjord\Crud\Models;

use Illuminate\Support\Str;
use Fjord\TrackEdits\TrackEdits;
use Fjord\EloquentJs\CanEloquentJs;
use Illuminate\Database\Eloquent\Model;

class CrudModel extends Model
{
    use TrackEdits,
        CanEloquentJs,
        Concerns\HasFields,
        Concerns\HasConfig;

    /**
     * Get translated attirbutes.
     *
     * @return void
     */
    public function translatedAttributes()
    {
        if (!is_translatable($this)) {
            return [];
        }

        return $this->translatedAttributes;
    }

    /**
     * Get config key.
     *
     * @return string $key
     */
    public function getConfigKey()
    {
        return "crud." . Str::snake(last(explode('\\', static::class)));
    }
}
