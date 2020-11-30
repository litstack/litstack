<?php

namespace Ignite\Crud\Fields\Traits;

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
     * Get field title.
     *
     * @return string
     */
    public function getTitle()
    {
        if (! $this->title) {
            return parent::getTitle();
        }

        return $this->title;
    }

    /**
     * Set default title.
     *
     * @return string
     */
    protected function setTitleDefault()
    {
        return $this->getTitle();
    }

    /**
     * Hide the title.
     *
     * @param  bool  $noTitle
     * @return $this
     */
    public function noTitle(bool $noTitle = true)
    {
        $this->setAttribute('no_title', $noTitle);

        return $this;
    }

    /**
     * Set field title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function title(string $title)
    {
        $this->setAttribute('title', $title);

        if (method_exists($this, 'placeholder') && ! $this->placeholder) {
            $this->placeholder($title);
        }

        return $this;
    }

    /**
     * Set field description.
     *
     * @param  string $info
     * @return $this
     */
    public function info(string $info)
    {
        $this->setAttribute('info', $info);

        return $this;
    }

    /**
     * Set hint.
     *
     * @param string $hint
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
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
     * @param bool $storable
     *
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
     * @param int|float $width
     *
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
     * @return bool
     */
    public function setStorableDefault()
    {
        return true;
    }

    /**
     * Set width attribute.
     *
     * @return int
     */
    public function setWidthDefault()
    {
        return 12;
    }
}
