<?php

namespace AwStudio\Fjord\Models\Traits;

trait HasRepeatables
{
    public function repeatables()
    {
        return $this->morphMany('AwStudio\Fjord\Models\Repeatable', 'model')->orderBy('order_column');
    }

    public function toArray()
    {
        $this->append('has_repeatables');

        return parent::toArray();
    }

    public function getHasRepeatablesAttribute()
    {
        return true;
    }
}
