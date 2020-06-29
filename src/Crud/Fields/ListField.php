<?php

namespace Fjord\Crud\Fields;

use Closure;
use Fjord\Crud\BaseForm;
use Fjord\Crud\RelationField;
use Fjord\Crud\Models\FormField;
use Fjord\Crud\Repositories\ListRepository;

class ListField extends RelationField
{
    use Traits\HasBaseField,
        Traits\FieldHasForm;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-list';

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
     * Set default attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        $this->search(true);
        $this->maxDepth(3);
    }

    /**
     * Set search.
     *
     * @param boolean $search
     * @return self
     */
    public function search(bool $search = true)
    {
        $this->setAttribute('search', $search);

        return $this;
    }

    /**
     * Preview title.
     *
     * @param string $title
     * @return self
     */
    public function previewTitle(string $title)
    {
        $this->setAttribute('previewTitle', $title);

        return $this;
    }

    /**
     * Set max deth.
     *
     * @param integer $depth
     * @return $this
     */
    public function maxDepth(int $depth)
    {
        $this->setAttribute('maxDepth', $depth);

        return $this;
    }

    /**
     * Add form to modal.
     *
     * @param Closure $closure
     * @return void
     */
    public function form(Closure $closure)
    {
        $form = new BaseForm($this->model);

        $form->afterRegisteringField(function ($field) {
            $field->setAttribute('params', [
                'field_id' => $this->id,
                'list_item_id' => null
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
     * @param boolean $query
     * @return mixed
     */
    public function getRelationQuery($model)
    {
        if (!$model instanceof FormField) {
            return $model->{$this->id}();
        }

        return $model->listItems($this->id);
    }
}
