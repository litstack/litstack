<?php

namespace Fjord\Crud\Fields\Concerns;

use InvalidArgumentException;

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
    public $formItemWrapperRequired = ['title'];

    /**
     * Set field title.
     *
     * @param string $title
     * @return self
     */
    public function title(string $title)
    {
        $this->setAttribute('title', $title);

        return $this;
    }

    /**
     * Set hint.
     *
     * @param string $hint
     * @return self
     */
    public function hint(string $hint)
    {
        if (property_exists($this, 'withoutHint')) {
            if ($this->withoutHint) {
                throw new InvalidArgumentException(sprintf(
                    'The hint method is not available on the %s field.',
                    class_basename(static::class)
                ));
            }
        }

        $this->setAttribute('hint', $hint);

        return $this;
    }

    /**
     * Set storable.
     *
     * @param boolean $storable
     * @return self
     */
    public function storable(bool $storable = true)
    {
        $this->setAttribute('storable', $storable);

        return $this;
    }

    /**
     * Set width columns or raito.
     *
     * @param integer|float $width
     * @return self
     */
    public function width($width)
    {
        $this->setAttribute('width', $width);

        return $this;
    }

    /**
     * Set storable attribute.
     *
     * @return void
     */
    public function setStorableDefault()
    {
        return true;
    }

    /**
     * Set width attribute.
     *
     * @return void
     */
    public function setWidthDefault()
    {
        return 12;
    }
}
