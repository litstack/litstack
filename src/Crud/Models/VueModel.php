<?php

namespace Fjord\Crud\Models;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;

class VueModel extends Model
{
    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        // Checking if toArray gets called from __toString to return Vue model 
        // attributes.
        foreach (debug_backtrace() as $trace) {
            if (!array_key_exists('function', $trace)) {
                continue;
            }

            if ($trace['function'] == 'toJson') {
                return $this->forVue();
            }
        }

        return parent::toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->forVue(), $options);
    }

    /**
     * Get attributes needed by Vue model.
     *
     * @return array
     */
    public function forVue()
    {
        $array = parent::toArray();

        return [
            'item' => (object) $array,
            'translatable' => is_translatable($this),
            'route' => $this->getTable(),
            'relations' => '',
            'collection' => false,
            'fields' => collect($this->fields)
        ];
    }
}
