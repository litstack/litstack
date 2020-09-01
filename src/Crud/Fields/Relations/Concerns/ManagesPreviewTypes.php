<?php

namespace Ignite\Crud\Fields\Relations\Concerns;

use Ignite\Exceptions\Traceable\InvalidArgumentException;

trait ManagesPreviewTypes
{
    /**
     * Relation Preview type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function type(string $type)
    {
        if (! array_key_exists($type, $this->availablePreviewTypes)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid preview type "%s" for relation field. Available types are: %s',
                    $type,
                    implode(', ', array_keys($this->availablePreviewTypes))
                ),
                [
                    'function' => 'type',
                ]
            );
        }

        $this->setAttribute('previewType', $type);

        $this->setRequiredAttributesForType($type);

        return $this;
    }

    protected function setRequiredAttributesForType(string $type)
    {
        foreach ($this->availablePreviewTypes as $previewType => $attributes) {
            foreach ($attributes as $attribute) {
                if ($previewType == $type) {
                    $this->required[] = $attribute;
                    continue;
                }

                if (($key = array_search($attribute, $this->required)) !== false) {
                    unset($this->required[$key]);
                }
            }
        }
    }
}
