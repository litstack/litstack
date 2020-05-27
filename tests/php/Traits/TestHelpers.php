<?php

namespace FjordTest\Traits;

use ReflectionClass;

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
        return $method->invokeArgs($instance, []);
    }
}
