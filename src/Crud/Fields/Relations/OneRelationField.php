<?php

namespace Ignite\Crud\Fields\Relations;

class OneRelationField extends LaravelRelationField
{
    use Concerns\ManagesPreviewTypes;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-relation';

    /**
     * Available preview types.
     *
     * @var array
     */
    protected $availablePreviewTypes = [
        'table' => [],
        'link'  => ['linkValue', 'related_route_prefix'],
    ];

    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function mount()
    {
        parent::mount();

        $this->setAttribute('many', false);
        $this->type('table');

        $this->small();
    }

    /**
     * Set link text.
     * Example: "{first_name} {last_name}".
     *
     * @param string $text
     *
     * @return $this
     */
    public function linkValue(string $text)
    {
        $this->setAttribute('linkValue', $text);

        return $this;
    }
}
