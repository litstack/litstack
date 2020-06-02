<?php

namespace Fjord\Crud\Fields\Relations\Concerns;

use InvalidArgumentException;

trait ManagesPreviewTypes
{
    /**
     * Relation Preview type.
     *
     * @param string $type
     * @return $this
     */
    public function type(string $type)
    {
        if (!array_key_exists($type, $this->availablePreviewTypes)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid preview type "%s" for relation field. Available types are: %s',
                    $type,
                    implode(', ', $this->availablePreviewTypes)
                )
            );
        }

        $this->setAttribute('previewType', $type);

        foreach ($this->availablePreviewTypes[$type] as $required) {
            $this->required[] = $required;
        }

        return $this;
    }
}
