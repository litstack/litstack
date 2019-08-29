<?php

namespace AwStudio\Fjord\Form;

use Illuminate\Support\Collection;

class FormFieldCollection extends Collection
{
    /**
     * Works also for nested Collections.
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array = $this->getArrays($array);

        return $array;
    }

    protected function getArrays($array) {

        if(! is_array($array)) {
            return $this->compileValue($array);
        }

        foreach($array as $key => $value) {
            $array[$key] = $this->getArrays($value);
        }

        return $array;
    }

    protected function compileValue($value)
    {
        if(gettype($value) != gettype((object) [])) {
            return $value;
        }

        if(! method_exists($value, 'toArray')) {
            return $value;
        }

        return $this->getArrays($value->toArray());
    }
}
