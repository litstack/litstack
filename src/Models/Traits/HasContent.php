<?php

namespace AwStudio\Fjord\Models\Traits;

trait HasContent
{
    function content()
    {
        return $this->hasMany('AwStudio\Fjord\Models\Content', 'model_id')
            ->where('model', get_class($this));
    }

    public function toArray()
    {
        $this->append('has_content');

        return parent::toArray();
    }

    public function getHasContentAttribute()
    {
        return true;
    }
}
