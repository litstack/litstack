<?php

namespace AwStudio\Fjord\Fjord\FormFields;

use Exception;
use ArrayAccess;

class FormField implements ArrayAccess
{
    const FIELDS = [
        'input' => Input::class,
        'wysiwyg' => WYSIWYG::class,
        'textarea' => TextArea::class,
        'boolean' => Boolean::class,
        'block' => Block::class,
        'relation' => Relation::class,
        'select' => Select::class,
        'image' => Image::class
    ];

    protected $attributes = [];

    protected $path;

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
        if(! $this->attributeExists('type')) {
            throw new Exception($this->getErrorMessage("Required form field key \"type\" missing"));
        }

        if(! array_key_exists($this->getAttribute('type'), self::FIELDS)) {
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

    protected function setDefaults(callable $callback = null)
    {

        // Required.
        foreach($this->getFieldClass()::REQUIRED as $key) {
            if(! $this->attributeExists($key)) {
                throw new Exception($this->getErrorMessage("Required form field key \"{$key}\" missing for {$this->config['type']} field"));
            }
        }

        // Defaults Callback.
        if($callback) {
            $callback($this);
            if(! $this->validAttributes()) {
                $info = closure_info($callback);
                throw new \Exception("FormField callback in returns invalid attributes" . $info->getFileName() . " on line " . $info->getStartLine() . " - " . $info->getEndLine());
            }
        }

        // Defaults.
        foreach($this->getFieldClass()::DEFAULTS as $key => $value) {

            if($this->attributeExists($key)) {
                continue;
            }

            $this->setAttribute($key, $value);
        }

        // Prepare
        if(method_exists($this->getFieldClass(), 'prepare')) {
            call_user_func_array([$this->getFieldClass(), 'prepare'], [$this, $this->path]);
        }

        if(! $this->getFieldClass()::TRANSLATABLE) {
            $this->setAttribute('translatable', false);
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
