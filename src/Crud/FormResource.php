<?php

namespace Ignite\Crud;

use Ignite\Crud\Models\LitFormModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FormResource extends JsonResource
{
    /**
     * A list of field ids that should be rendered by the resource. If only is
     * null, all fields will be rendered.
     *
     * @var array|null
     */
    protected $only;

    /**
     * The resource instance.
     *
     * @var LitFormModel
     */
    public $resource;

    /**
     * Create a new resource instance.
     *
     * @param  LitFormModel $resource
     * @return void
     */
    public function __construct(LitFormModel $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
        ];

        foreach ($this->fields as $field) {
            if (! $this->shouldBeRendered($field)) {
                continue;
            }

            $value = $this->getAttribute($field->local_key);

            if ($value instanceof Collection) {
                $value = $value->map(function ($item) use ($request) {
                    if ($item instanceof LitFormModel) {
                        return $item->resource()->toArray($request);
                    }

                    return $item;
                })->toArray();
            }

            $data[$field->id] = $value;
        }

        return $data;
    }

    /**
     * Set field ids that shouldn't be rendered by the resource.
     *
     * @param  array ...$except
     * @return $this
     */
    public function except(...$except)
    {
        $except = Arr::flatten($except);

        return $this->only(
            collect($this->getFieldIds())->filter(fn ($id) => ! in_array($id, $except))
        );
    }

    /**
     * Set field ids that should be rendered by the resource.
     *
     * @param  array ...$only
     * @return $this
     */
    public function only(...$only)
    {
        if (count($only) > 1) {
            $this->only = $only;
        } elseif ($only[0] instanceof Collection) {
            $this->only = Arr::wrap($only[0]->values()->toArray());
        } else {
            $this->only = Arr::wrap($only[0]);
        }

        return $this;
    }

    /**
     * Get except ids.
     *
     * @return array
     */
    public function getExcept()
    {
        return collect($this->getFieldIds())
            ->filter(fn ($id) => ! in_array($id, $this->only))
            ->values()
            ->toArray();
    }

    /**
     * Get only ids.
     *
     * @return array
     */
    public function getOnly()
    {
        if (is_null($this->only)) {
            return $this->getFieldIds();
        }

        return $this->only;
    }

    /**
     * Determine if a field should be rendered by the resource.
     *
     * @param  string $field
     * @return bool
     */
    public function shouldBeRendered($field)
    {
        if ($field instanceof Field) {
            $field = $field->local_key;
        }

        return in_array($field, $this->getOnly());
    }
}
