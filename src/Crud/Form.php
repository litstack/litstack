<?php

namespace Fjord\Crud;

use Fjord\Support\VueProp;
use Fjord\Crud\Fields\Code;
use Fjord\Crud\Fields\Icon;
use Fjord\Crud\Fields\Input;
use Fjord\Crud\Fields\Range;
use Fjord\Crud\Fields\Select;
use InvalidArgumentException;
use Fjord\Crud\Fields\Boolean;
use Fjord\Crud\Fields\Wysiwyg;
use Fjord\Crud\Fields\Datetime;
use Fjord\Crud\Fields\Textarea;
use Fjord\Crud\Models\FormField;
use Fjord\Crud\Fields\Checkboxes;
use Fjord\Crud\Fields\Media\Image;
use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Crud\Fields\Relations\HasOne;
use Fjord\Crud\Fields\Relations\HasMany;
use Fjord\Crud\Fields\Relations\MorphOne;
use Fjord\Crud\Fields\Relations\BelongsTo;
use Fjord\Crud\Fields\Relations\MorphMany;
use Fjord\Crud\Fields\Relations\MorphToMany;
use Fjord\Crud\Fields\Relations\OneRelation;
use Fjord\Crud\Fields\Relations\ManyRelation;
use Fjord\Exceptions\MethodNotFoundException;
use Fjord\Crud\Fields\Relations\BelongsToMany;

class Form extends VueProp
{
    /**
     * Available fields.
     *
     * @var array
     */
    protected $fields = [
        'input' => Input::class,
        'select' => Select::class,
        'boolean' => Boolean::class,
        'code' => Code::class,
        'icon' => Icon::class,
        'datetime' => Datetime::class,
        'dt' => Datetime::class,
        'checkboxes' => Checkboxes::class,
        'range' => Range::class,
        'textarea' => Textarea::class,
        'text' => Textarea::class,
        'wysiwyg' => Wysiwyg::class,
        'blocks' => Blocks::class,
        'image' => Image::class,
        'oneRelation' => OneRelation::class,
        'manyRelation' => ManyRelation::class,
    ];

    /**
     * Available relations.
     *
     * @var array
     */
    protected $relations = [
        \Illuminate\Database\Eloquent\Relations\BelongsToMany::class => BelongsToMany::class,
        \Illuminate\Database\Eloquent\Relations\BelongsTo::class => BelongsTo::class,
        \Illuminate\Database\Eloquent\Relations\MorphOne::class => MorphOne::class,
        \Illuminate\Database\Eloquent\Relations\MorphToMany::class => MorphToMany::class,
        \Illuminate\Database\Eloquent\Relations\MorphMany::class => MorphMany::class,
        \Illuminate\Database\Eloquent\Relations\HasMany::class => HasMany::class,
        \Illuminate\Database\Eloquent\Relations\HasOne::class => HasOne::class,
    ];

    /**
     * Model class.
     *
     * @var string
     */
    protected $model;

    /**
     * Field that is being registered is stored in here. When the next 
     * field is called this field will be checked for required properties. 
     *
     * @var Field
     */
    protected $registrar;

    /**
     * Registered fields.
     *
     * @var array 
     */
    protected $registeredFields = [];

    /**
     * Create new Form instance.
     *
     * @param string $model
     */
    public function __construct(string $model)
    {
        $this->model = $model;

        $this->registeredFields = collect([]);
    }

    /**
     * Register new Field.
     *
     * @param mixed $field
     * @param string $id
     * @param array $params
     * @return Field $field
     */
    protected function registerField($field, string $id, $params = [])
    {
        if ($this->registrar) {
            // Check if all required properties are set.
            $this->registrar->checkComplete();
        }

        $fieldInstance = new $field($id, $this->model);

        $this->registrar = $fieldInstance;
        $this->registeredFields[] = $fieldInstance;

        return $fieldInstance;
    }

    /**
     * Register new Relation.
     *
     * @param string $name
     * @return mixed
     * 
     * @throws \InvalidArgumentException
     */
    public function relation(string $name)
    {
        if ($this->model == FormField::class) {
            throw new InvalidArgumentException('Relation field is not available for forms. Use oneRelation or manyRelation instead to create a relation.');
        }

        $relationType = get_class((new $this->model)->$name());

        if (array_key_exists($relationType, $this->relations)) {
            return $this->registerField($this->relations[$relationType], $name);
        }

        throw new InvalidArgumentException(sprintf(
            'Relation %s not supported. Supported relations: %s',
            lcfirst(class_basename($relationType)),
            implode(', ', collect(array_keys($this->relations))->map(function ($relation) {
                return lcfirst(class_basename($relation));
            })->toArray())
        ));
    }

    /**
     * Get current card.
     *
     * @return array $card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Get registered fields.
     *
     * @return void
     */
    public function getRegisteredFields()
    {
        return $this->registeredFields;
    }

    /**
     * Find registered field.
     *
     * @param string $fieldId
     * @return Field|void
     */
    public function findField(string $fieldId)
    {
        foreach ($this->registeredFields as $field) {
            if ($field->id == $fieldId) {
                return $field;
            }
        }
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function getArray(): array
    {
        return [
            'fields' => $this->registeredFields
        ];
    }

    /**
     * Throw a method not allowed HTTP exception.
     *
     * @param  array  $others
     * @param  string  $method
     * @return void
     *
     * @throws \Fjord\Exceptions\MethodNotFoundException
     */
    protected function methodNotAllowed($method)
    {
        throw new MethodNotFoundException(
            sprintf(
                "The %s method is not found for this form. Supported fields: %s.",
                $method,
                implode(', ', array_merge(['relation'], array_keys($this->fields))),
            )
        );
    }

    /**
     * Call form method.
     *
     * @param string $method
     * @param array $params
     * @return void
     * 
     * @throws \Fjord\Exceptions\MethodNotFoundException
     */
    public function __call($method, $params = [])
    {
        if (array_key_exists($method, $this->fields)) {
            return $this->registerField($this->fields[$method], ...$params);
        }

        $this->methodNotAllowed($method);
    }
}
