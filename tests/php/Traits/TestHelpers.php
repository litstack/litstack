<?php

namespace FjordTest\Traits;

use ReflectionClass;
use ReflectionProperty;

trait TestHelpers
{
    /**
     * Pass thru all method except for the given names.
     *
     * @param mock $mock
     * @param mixed $class
     * @param array $without
     * @return void
     */
    protected function passthruAllExcept($mock, $class, array $without)
    {
        $methods = get_class_methods($class);
        foreach ($without as $method) {
            unset($methods[array_search($method, $methods)]);
        }
        $mock->shouldReceive(...$methods)->passthru();
    }

    /**
     * Calling protected or private class method.
     *
     * @param mixed $instance
     * @param string $method
     * @param array $params
     * @return mixed
     */
    protected function callUnaccessibleMethod($instance, string $method, array $params = [])
    {
        $class = get_class($instance);
        $class = new ReflectionClass($class);
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($instance, $params);
    }

    /**
     * Set protected or private class property value.
     *
     * @param mixed $instance
     * @param string $property
     * @param mixed $value
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
     * @param mixed $instance
     * @param string $property
     * @param mixed $value
     * @return mixed
     */
    public function getUnaccessibleProperty($instance, string $property)
    {
        $reflection = new ReflectionProperty(get_class($instance), $property);
        $reflection->setAccessible(true);
        return $reflection->getValue($instance);
    }

    /**
     * Assert class has trait.
     *
     * @param string $trait
     * @param string|mixed $class
     * @return void
     */
    public function assertHasTrait(string $trait, $class)
    {
        $traits = array_flip(class_uses_recursive($class));
        $this->assertArrayHasKey($trait, $traits);
    }
}
