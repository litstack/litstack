<?php

namespace Fjord\Crud;

use Fjord\Support\VueProp;
use Fjord\Crud\Models\FormBlock;
use Fjord\Crud\Models\FormField;

class CrudJs extends VueProp
{
    /**
     * Model.
     *
     * @var mixed
     */
    protected $model;

    /**
     * Create new CrudJs instance.
     *
     * @param mixed $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Get array.
     *
     * @return array
     */
    public function getArray(): array
    {
        return [
            'attributes' => $this->getModelAttributes(),
            'translatable' => is_translatable($this->model),
            'cast' => $this->usesJsonCast()
        ];
    }

    /**
     * Model to array.
     *
     * @return array
     */
    public function getModelAttributes()
    {
        $array = $this->model->toArray();

        if (!array_key_exists('translations', $array)) {
            return $array;
        }

        // Setting translation attributes that are used for updating the model.
        // (de, en, ...)
        $array = array_merge($this->model->getTranslationsArray(), $array);

        return $array;
    }

    /**
     * Is the Crud Model casting the field values into one json column.
     *
     * @return boolean
     */
    public function usesJsonCast()
    {
        return $this->model instanceof FormField
            || $this->model instanceof FormBlock;
    }
}
