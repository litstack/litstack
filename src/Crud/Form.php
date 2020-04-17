<?php

namespace Fjord\Crud;

use BadMethodCallException;
use Fjord\Crud\Fields\Code;
use Fjord\Crud\Fields\Icon;
use Fjord\Crud\Fields\Input;
use Fjord\Crud\Fields\Range;
use Fjord\Crud\Fields\Boolean;
use Fjord\Crud\Fields\Wysiwyg;
use Fjord\Crud\Fields\Datetime;
use Fjord\Crud\Fields\Textarea;
use Fjord\Crud\Fields\Checkboxes;
use Fjord\Crud\Fields\Blocks\Blocks;
use Fjord\Application\Config\ConfigItem;
use Fjord\Crud\Fields\Relations\OneRelation;
use Fjord\Crud\Fields\Relations\ManyRelation;
use Fjord\Exceptions\MethodNotFoundException;
use ReflectionClass;

class Form extends ConfigItem
{
    /**
     * Available fields.
     *
     * @var array
     */
    protected $fields = [
        'input' => Input::class,
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
        'oneRelation' => OneRelation::class,
        'manyRelation' => ManyRelation::class,
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
     * @param string $name
     * @param string $id
     * @param array $params
     * @return Field $field
     */
    protected function registerField(string $name, string $id, $params = [])
    {
        if ($this->registrar) {
            // Check if all required properties are set.
            $this->registrar->checkComplete();
        }

        $field = new $this->fields[$name]($id, $this->model);

        $this->registrar = $field;
        $this->registeredFields[] = $field;

        return $field;
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
                implode(', ', array_keys($this->fields)),
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
            return $this->registerField($method, ...$params);
        }

        $this->methodNotAllowed($method);
    }
}
