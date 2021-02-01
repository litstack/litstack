<?php

namespace Ignite\Crud\Repositories;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudBaseController;
use Ignite\Crud\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

abstract class BaseFieldRepository
{
    /**
     * Field instance.
     *
     * @var \Ignite\Crud\Field
     */
    protected $field;

    /**
     * Crud controller instance.
     *
     * @var CrudBaseController
     */
    protected $contoller;

    /**
     * Form instance.
     *
     * @var BaseForm
     */
    protected $form;

    /**
     * Create new BaseFieldRepository instance.
     *
     * @param  ConfigHandler      $config
     * @param  CrudBaseController $controller
     * @param  BaseForm           $form
     * @param  Field              $field
     * @return void
     */
    public function __construct(ConfigHandler $config, CrudBaseController $controller, BaseForm $form, Field $field = null)
    {
        $this->config = $config;
        $this->controller = $controller;
        $this->form = $form;
        $this->field = $field;
    }

    /**
     * Set form.
     *
     * @param  BaseForm $form
     * @return void
     */
    public function setForm(BaseForm $form)
    {
        $this->form = $form;
    }

    /**
     * Fill attributes to model.
     *
     * @param  mixed $model
     * @param  array $attributes
     * @param  array $fields
     * @return void
     */
    protected function fillAttributesToModel($model, array $attributes)
    {
        foreach ($this->form->getRegisteredFields() as $field) {
            if (! array_key_exists($field->local_key, $attributes)) {
                continue;
            }

            $field->fillModel($model, $field->local_key, $attributes[$field->local_key]);
        }
    }

    /**
     * Get registered fields.
     *
     * @return Collection
     */
    protected function getRegisteredFields(): Collection
    {
        if (method_exists('getRegisteredFields', $this->field)) {
            return $this->field->getRegisteredFields();
        }

        return collect([]);
    }

    /**
     * Filter request attributes.
     *
     * @param  array      $attributes
     * @param  Collection $fields
     * @return array
     */
    protected function formatAttributes(array $attributes, $fields)
    {
        foreach ($fields as $field) {
            if (! array_key_exists($field->local_key, $attributes)) {
                continue;
            }

            // Format value before update.
            if (method_exists($field, 'format')) {
                $attributes[$field->local_key] = $field->format(
                    $attributes[$field->local_key]
                );
            }
        }

        return $attributes;
    }

    /**
     * Order models.
     *
     * @param  Builder $query
     * @param  Field   $field
     * @param  array   $ids
     * @return void
     */
    public function orderField($query, Field $field, array $ids)
    {
        $orderColumn = $field->orderColumn ?? 'order_column';

        foreach ($ids as $order => $id) {
            if ($query instanceof BelongsToMany) {
                (clone $query)->updateExistingPivot($id, [$orderColumn => $order]);
            } else {
                (clone $query)->where(
                    $query->getModel()->getQualifiedKeyName(), $id
                )->update([$orderColumn => $order]);
            }
        }
    }
}
