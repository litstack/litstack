<?php

namespace Ignite\Crud\Fields\Relations;

use Ignite\Crud\BaseForm;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Traits\ForwardsCalls;

class PivotField extends BaseForm
{
    use ForwardsCalls;

    /**
     * RelationForm instance.
     *
     * @var RelationForm
     */
    protected $form;

    /**
     * Create new RelationPivotForm instance.
     *
     * @param  RelationForm $form
     * @return void
     */
    public function __construct(RelationForm $form)
    {
        $this->form = $form;
    }

    /**
     * Forward calls to RelationForm.
     *
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $field = $this->forwardCallTo($this->form, $method, $parameters);
        $field->setAttribute('is_pivot', true);

        $this->form
            ->getParentField()
            ->query(function ($query) use ($field) {
                if ($query instanceof BelongsToMany) {
                    $query->withPivot($field->id);
                }
            });

        return $field;
    }
}
