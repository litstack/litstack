<?php

namespace Fjord\Vue;

use Exception;
use Fjord\Support\VueProp;

class Col extends VueProp
{
    use Concerns\IsTableCol;

    /**
     * Create new Col instance.
     *
     * @param string $label
     */
    public function __construct(string $label = null)
    {
        if ($label) {
            $this->label($label);
        }
    }

    /**
     * Get attributes
     *
     * @return array
     */
    public function getArray(): array
    {
        $this->checkComplete();

        return $this->attributes;
    }

    /**
     * Set value.
     *
     * @param string $value
     * @return self
     */
    public function value(string $value)
    {
        $this->attributes['value'] = $value;

        return $this;
    }

    /**
     * Check if all required props have been set.
     *
     * @return boolean
     * 
     * @throws \Exception
     */
    public function checkComplete()
    {
        if (array_key_exists('label', $this->attributes)) {
            return true;
        }

        throw new Exception(sprintf(
            'Missing required attributes: [%s] for table column.',
            implode(', ', ['label']),
        ));
    }
}
