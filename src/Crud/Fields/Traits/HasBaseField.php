<?php

namespace Fjord\Crud\Fields\Traits;

use InvalidArgumentException;

trait HasBaseField
{
    /**
     * Required base field attributes.
     *
     * @var array
     */
    public $baseFieldRequired = ['title'];

    /**
     * Set field title.
     *
     * @param string $title
     * @return $this
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
     * @return $this
     * 
     * @throws \InvalidArgumentException
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
     * @return $this
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
     * @return $this
     */
    public function width($width)
    {
        $this->setAttribute('width', $width);

        return $this;
    }

    /**
     * Set storable attribute.
     *
     * @return boolean
     */
    public function setStorableDefault()
    {
        return true;
    }

    /**
     * Set width attribute.
     *
     * @return integer
     */
    public function setWidthDefault()
    {
        return 12;
    }
}
