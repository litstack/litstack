<?php

namespace Fjord\Vue;

use Exception;
use Fjord\Contracts\Vue\Authorizable as AuthorizableContract;
use Fjord\Support\VueProp;
use Fjord\Vue\Traits\Authorizable;

class Component extends VueProp implements AuthorizableContract
{
    use Authorizable;

    /**
     * Vue component name.
     *
     * @var string
     */
    protected $name;

    /**
     * Vue component props.
     *
     * @var array
     */
    protected $props = [];

    /**
     * Create new Component instance.
     *
     * @param  string $name
     * @param  array  $options
     * @return void
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        $this->beforeMount();
    }

    /**
     * Before mount lifecycle hook.
     *
     * @return void
     */
    protected function beforeMount()
    {
        //
    }

    /**
     * Mounted lifecycle hook.
     *
     * @return void
     */
    protected function mounted()
    {
        //
    }

    /**
     * Available props.
     *
     * @return array
     */
    protected function props()
    {
        return [];
    }

    /**
     * Bind multiple props.
     *
     * @param  array $props
     * @return self
     */
    public function bind(array $props)
    {
        foreach ($props as $name => $value) {
            $this->prop($name, $value);
        }

        return $this;
    }

    /**
     * Add single prop.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return self
     */
    public function prop(string $name, $value = true)
    {
        $this->props[$name] = $value;

        return $this;
    }

    /**
     * Set component class.
     *
     * @param  string $value
     * @return void
     */
    public function class(string $value)
    {
        if (! $this->hasProp('class')) {
            return $this->prop('class', $value);
        }

        $this->props['class'] .= " {$value}";

        return $this;
    }

    /**
     * Get component name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get props.
     *
     * @return array
     */
    public function getProps()
    {
        return $this->props;
    }

    /**
     * Get prop by name.
     *
     * @param  string $name
     * @return mixed
     */
    public function getProp($name)
    {
        return $this->props[$name] ?? null;
    }

    /**
     * Check's if a prop has been set.
     *
     * @return bool
     */
    public function hasProp($name)
    {
        return array_key_exists($name, $this->props);
    }

    /**
     * Get component attribute.
     *
     * @param  string $name
     * @return void
     */
    public function __get(string $name)
    {
        if ($this->hasProp($name)) {
            return $this->getProp($name);
        }

        return $this->{$name};
    }

    /**
     * Get missing props.
     *
     * @return array
     */
    protected function getMissing()
    {
        return [];
    }

    /**
     * Check for missing required props.
     *
     * @throws \Exception
     * @return bool
     */
    public function checkComplete()
    {
        if (empty($missing = $this->getMissing())) {
            return true;
        }

        throw new Exception(sprintf(
            'Missing required props: [%s] for component "%s"',
            implode(', ', $missing),
            $this->name
        ));
    }

    /**
     * Get array.
     *
     * @return array
     */
    public function render(): array
    {
        $this->checkComplete();

        $this->mounted();

        return [
            'name'  => $this->name,
            'props' => collect($this->props),
        ];
    }
}
