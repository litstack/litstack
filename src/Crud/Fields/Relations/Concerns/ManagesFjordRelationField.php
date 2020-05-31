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
        $this->relatedModelClass = $model;
        $this->setAttribute('model', $model);

        // Load related config.
        $this->loadRelatedConfig($model);

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
