<?php

namespace Fjord\Crud\Fields\Relations\Concerns;

trait ManagesFjordRelationField
{
    /**
     * Set related model.
     *
     * @param string $mode
     * @return void
     */
    public function model(string $model)
    {
        $this->setRelatedModelClass($model);

        $this->setAttribute('model', $model);

        $this->setOrderDefaults();

        if (!$this->query) {
            $this->query = $model::query();
        }

        return $this;
    }

    /**
     * Set model and query builder.
     *
     * @return void
     */
    protected function initializeRelationField()
    {
    }
}
