<?php

namespace Fjord\Crud\Fields\Relations;

class OneRelationField extends LaravelRelationField
{
    use Concerns\ManagesPreviewTypes;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-relation';

    /**
     * Available preview types.
     *
     * @var array
     */
    protected $availablePreviewTypes = [
        'table' => ['preview'],
        'link' => ['linkText']
    ];

    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        parent::setDefaultAttributes();

        $this->setAttribute('many', false);
        $this->type('link');

        $this->small();
    }

    /**
     * Set link text.
     * Example: "{first_name} {last_name}"
     *
     * @param string $text
     * @return $this
     */
    public function linkText(string $text)
    {
        $this->setAttribute('linkText', $text);

        return $this;
    }
}
