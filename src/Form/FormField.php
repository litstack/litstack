<?php

namespace Fjord\Form;

use Closure;
use Exception;
use ArrayAccess;
use Illuminate\Support\Str;
use Fjord\Form\FormFields\Code;
use Fjord\Form\FormFields\Icon;
use Fjord\Form\FormFields\Block;
use Fjord\Form\FormFields\Image;
use Fjord\Form\FormFields\Input;
use Fjord\Form\FormFields\Range;
use Fjord\Form\FormFields\HasOne;
use Fjord\Form\FormFields\Select;
use Fjord\Form\FormFields\Boolean;
use Fjord\Form\FormFields\WYSIWYG;
use Fjord\Form\FormFields\DateTime;
use Fjord\Form\FormFields\TextArea;
use Fjord\Form\FormFields\Checkboxes;
use Fjord\Form\FormFields\FormHeader;
use Fjord\Form\FormFields\EditHasMany;
use Fjord\Form\FormFields\Relations\HasMany;
use Fjord\Form\FormFields\Relations\MorphTo;
use Fjord\Form\FormFields\Relations\MorphOne;
use Fjord\Form\FormFields\Relations\Relation;
use Fjord\Form\FormFields\Relations\BelongsTo;
use Fjord\Form\FormFields\Relations\MorphMany;
use Fjord\Form\FormFields\Relations\MorphToMany;
use Fjord\Form\FormFields\Relations\BelongsToMany;
use Fjord\Form\FormFields\Relations\MorphedByMany;

class FormField implements ArrayAccess
{
    const FIELDS = [
        'icon' => Icon::class,
        'input' => Input::class,
        'wysiwyg' => WYSIWYG::class,
        'textarea' => TextArea::class,
        'boolean' => Boolean::class,
        'block' => Block::class,
        'relation' => Relation::class,
        'select' => Select::class,
        'image' => Image::class,
        'checkboxes' => Checkboxes::class,
        'code' => Code::class,

        'datetime' => DateTime::class,
        'dt' => DateTime::class,

        'form_header' => FormHeader::class,

        'range' => Range::class,

        'hasOne' => HasOne::class,
        'belongsTo' => BelongsTo::class,
        'morphOne' => MorphOne::class,
        'morphTo' => MorphTo::class,

        'hasMany' => HasMany::class,
        'editHasMany' => EditHasMany::class,
        'belongsToMany' => BelongsToMany::class,
        'morphMany' => MorphMany::class,
        'morphToMany' => MorphToMany::class,
        'morphedByMany' => MorphedByMany::class
    ];

    protected $attributes = [];

    protected $path;

    protected $model;

    /**
     * @var Array  $config
     * @var String $path For Error logging.
     */
    public function __construct(array $attributes = [], string $path = '', callable $setDefaultsCallback = null)
    {
        $this->attributes = $attributes;
        $this->path = $path;

        $this->isValidType();

        $this->setDefaults($setDefaultsCallback);
    }

    public function offsetExists($offset)
    {
        return $this->attributeExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->setAttribute($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->unsetAttribute($offset);
    }

    protected function isValidType()
    {
        if (!$this->attributeExists('type')) {
            throw new Exception($this->getErrorMessage("Required form field key \"type\" missing"));
        }

        if (!array_key_exists($this->getAttribute('type'), self::FIELDS)) {
            throw new Exception($this->getErrorMessage("Invalid form field type \"{$this->attributes['type']}\""));
        }

        return true;
    }

    public function validAttributes()
    {
        return gettype($this->attributes) == gettype([]);
    }

    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function unsetAttribute($key)
    {
        unset($this->attributes[$key]);
    }

    public function attributeExists($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    protected function getErrorMessage($message)
    {
        return  $message . ($this->path
            ? " in {$this->path}."
            : ".");
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function getFieldClass()
    {
        return self::FIELDS[$this->type];
    }

    public function setModel(string $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set default values for the Form Field.
     *
     * @param callable $callback
     * @return void
     */
    protected function setDefaults(callable $callback = null)
    {

        // Required.
        foreach ($this->getFieldClass()::REQUIRED as $key) {
            if (!$this->attributeExists($key)) {
                throw new Exception($this->getErrorMessage("Required form field key \"{$key}\" missing for {$this->config['type']} field"));
            }
        }

        // Always set $field->local_key.
        if (!$this->attributeExists('local_key')) {
            $this->setAttribute('local_key', $this->attributes['id']);
        }

        // Defaults Callback.
        if ($callback) {
            $callback($this);
            if (!$this->validAttributes()) {
                $info = closure_info($callback);
                throw new \Exception("FormField callback in returns invalid attributes" . $info->getFileName() . " on line " . $info->getStartLine() . " - " . $info->getEndLine());
            }
        }

        // Defaults.
        foreach ($this->getFieldClass()::DEFAULTS as $key => $value) {

            if ($this->attributeExists($key)) {
                continue;
            }

            $this->setAttribute($key, $value);
        }

        // Prepare
        if (method_exists($this->getFieldClass(), 'prepare')) {
            call_user_func_array([$this->getFieldClass(), 'prepare'], [$this, $this->path]);
        }

        // Force
        if (defined($this->getFieldClass() . '::TRANSLATABLE') && !$this->getFieldClass()::TRANSLATABLE) {
            $this->setAttribute('translatable', false);
        }

        // Readonly
        if (is_closure($this->attributes['readonly'])) {
            $closure = Closure::bind($this->attributes['readonly'], $this, self::class);
            $this->attributes['readonly'] = $closure(fjord_user());
        }
    }


    public function __set($key, $value)
    {
        return $this->setAttribute($key, $value);
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }
}
