<?php

namespace Fjord\Crud\Fields\Concerns;

/**
 * @property-read boolean $withoutHint
 */
trait FormItemWrapper
{
    /**
     * Required attributes.
     *
     * @var array
     */
    public $requiredFormItemWrapperAttributes = [
        'title',
    ];

    /**
     * Available field attributes.
     *
     * @var array
     */
    public $availableFormItemWrapperAttributes = [
        'title',
        'hint',
    ];

    /**
     * Set hint attribute.
     *
     * @return void
     */
    public function setHintAttribute()
    {
        if (!property_exists($this, 'withoutHint')) {
            return;
        }

        if ($this->withoutHint) {
            $this->removeAvailableAttribute('hint');
        }
    }
}
