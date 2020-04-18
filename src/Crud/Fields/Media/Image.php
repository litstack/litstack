<?php

namespace Fjord\Crud\Fields;

use Fjord\Crud\Models\FormField;
use Fjord\Crud\ManyRelationField;

class Image extends ManyRelationField
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-form-image';

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'title',
        'hint',
        'icons',
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * Get relation for model.
     *
     * @param mixed $model
     * @param boolean $query
     * @return mixed
     */
    protected function getRelation($model)
    {
        if (!$model instanceof FormField) {
            return parent::getRelation($model);
        }

        return $model->media()
            ->where('media.collection_name', $this->id);
    }
}
