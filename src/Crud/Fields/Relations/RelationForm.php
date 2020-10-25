<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Crud\BaseForm;

class RelationForm extends BaseForm
{
    /**
     * Relation field.
     *
     * @var LaravelRelationField
     */
    protected $field;

    /**
     * Determine's what the form can be used for.
     *
     * @var array
     */
    protected $allow = [
        'create' => true,
        'update' => true,
    ];

    /**
     * Create new RelationForm instance.
     *
     * @param  string               $model
     * @param  LaravelRelationField $field
     * @return void
     */
    public function __construct($model, LaravelRelationField $field)
    {
        parent::__construct($model);
        $this->field = $field;
    }

    /**
     * Get relation field.
     *
     * @return LaravelRelationField
     */
    public function getParentField()
    {
        return $this->field;
    }

    /**
     * Get pivot field.
     *
     * @return void
     */
    public function pivot()
    {
        return new PivotField($this);
    }

    /**
     * Allow using form for updating a connected related model.
     *
     * @param  bool  $allow
     * @return $this
     */
    public function allowUpdate(bool $allow = true)
    {
        $this->allow['update'] = $allow;

        return $this;
    }

    /**
     * Determine if the form can be used for updating a Model.
     *
     * @return bool
     */
    public function allowsUpdate(): bool
    {
        return $this->allow['update'];
    }

    /**
     * Allow using form for creating a new related model.
     *
     * @param  bool  $allow
     * @return $this
     */
    public function allowCreate(bool $allow = true)
    {
        $this->allow['create'] = $allow;

        return $this;
    }

    /**
     * Determine if the form can be used for Model creation.
     *
     * @return bool
     */
    public function allowsCreate(): bool
    {
        return $this->allow['create'];
    }

    /**
     * Render RelationForm.
     *
     * @return array
     */
    public function render(): array
    {
        $rendered = parent::render();
        $rendered['allow'] = $this->allow;

        return $rendered;
    }
}
