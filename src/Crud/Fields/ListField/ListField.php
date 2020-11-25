<?php

namespace Ignite\Crud\Fields\ListField;

use Closure;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Fields\Traits\FieldHasForm;
use Ignite\Crud\Fields\Traits\HasBaseField;
use Ignite\Crud\Models\Form;
use Ignite\Crud\Models\ListItem;
use Ignite\Crud\RelationField;
use Ignite\Crud\Repositories\ListRepository;

class ListField extends RelationField
{
    use HasBaseField, FieldHasForm;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-list';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = ['form', 'previewTitle'];

    /**
     * Repository class.
     *
     * @return string
     */
    protected $repository = ListRepository::class;

    /**
     * Authorized Closure.
     *
     * @var Closure
     */
    protected $authorizeItemClosure;

    /**
     * Set default attributes.
     *
     * @return void
     */
    public function mount()
    {
        $this->maxDepth(3);
    }

    /**
     * Preview title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function previewTitle(string $title)
    {
        $this->setAttribute('previewTitle', $title);

        return $this;
    }

    /**
     * Set max deth.
     *
     * @param int $depth
     *
     * @return $this
     */
    public function maxDepth(int $depth)
    {
        $this->setAttribute('maxDepth', $depth);

        return $this;
    }

    /**
     * Add authorize closure.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function authorizeItem(Closure $closure)
    {
        $this->authorizeItemClosure = $closure;

        return $this;
    }

    /**
     * Determines if the given item is authorized.
     *
     * @param  ListItem $item
     * @return bool
     */
    public function itemAuthorized(ListItem $item = null, $operation)
    {
        if (! $this->authorizeItemClosure) {
            return true;
        }

        return call_user_func($this->authorizeItemClosure, lit_user(), $item, $operation);
    }

    /**
     * Add form to modal.
     *
     * @param Closure $closure
     *
     * @return void
     */
    public function form(Closure $closure)
    {
        $form = new BaseForm($this->model);

        $form->registered(function ($field) {
            $field->mergeOrSetAttribute('params', [
                'field_id'     => $this->id,
                'list_item_id' => null,
            ]);
        });

        $form->setRoutePrefix(
            "$this->route_prefix/list"
        );

        $closure($form);

        $this->setAttribute('form', $form);

        return $this;
    }

    /**
     * Get relation query for model.
     *
     * @param mixed $model
     * @param bool  $query
     *
     * @return mixed
     */
    public function getRelationQuery($model)
    {
        if (! $model instanceof Form) {
            return $model->{$this->id}();
        }

        return $model->listItems($this->id);
    }
}
