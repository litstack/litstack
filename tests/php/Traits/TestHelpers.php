<?php

namespace Tests\Traits;

use ReflectionClass;
use ReflectionProperty;

trait TestHelpers
{
    /**
     * Calling protected or private class method.
     *
     * @param mixed|string $abstract
     * @param string       $method
     * @param array        $params
     *
     * @return mixed
     */
    protected function callUnaccessibleMethod($abstract, string $method, array $params = [])
    {
        $class = $abstract;
        if (! is_string($abstract)) {
            $class = get_class($abstract);
        }

        $class = new ReflectionClass($class);
        $method = $class->getMethod($method);
        $method->setAccessible(true);

        if ($method->isStatic()) {
            return $method->invokeArgs(null, $params);
        }

        return $method->invokeArgs($abstract, $params);
    }

    /**
     * Set protected or private class property value.
     *
     * @param mixed  $instance
     * @param string $property
     * @param mixed  $value
     *
     * @return void
     */
    public function setUnaccessibleProperty($instance, string $property, $value)
    {
        $reflection = new ReflectionProperty(get_class($instance), $property);
        $reflection->setAccessible(true);
        $value = $reflection->setValue($instance, $value);
    }

    /**
     * Get protected or private class property value.
     *
     * @param  mixed  $object
     * @param  string $property
     * @param  mixed  $value
     * @return mixed
     */
    public function getUnaccessibleProperty($object, string $property)
    {
        if (! is_string($object)) {
            $class = get_class($object);
        } else {
            $class = $object;
        }

        $reflection = new ReflectionProperty($class, $property);
        $reflection->setAccessible(true);

        return $reflection->getValue($object);
    }

    /**
     * Assert class has trait.
     *
     * @param string       $trait
     * @param string|mixed $class
     *
     * @return void
     */
    public function assertHasTrait(string $trait, $class)
    {
        $traits = array_flip(class_uses_recursive($class));
        $this->assertArrayHasKey($trait, $traits);
    }
}
