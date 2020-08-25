<?php

namespace Tests\Config;

use Ignite\Support\Facades\Config;
use Tests\BackendTestCase;

class ConfigLoaderTest extends BackendTestCase
{
    /** @test */
    public function test_getNamespaceFromKey_method()
    {
        $className = Config::getNamespaceFromKey('my.config.key');
        $this->assertEquals('Lit\Config\My\Config\KeyConfig', $className);

        $className = Config::getNamespaceFromKey('my.config.snake_key');
        $this->assertEquals('Lit\Config\My\Config\SnakeKeyConfig', $className);
    }

    /** @test */
    public function test_getKey_method()
    {
        $key = Config::getKey('Lit\Config\My\Config\KeyConfig');
        $this->assertEquals('my.config.key', $key);

        $key = Config::getKey('Lit\Config\My\Config\SnakeKeyConfig');
        $this->assertEquals('my.config.snake_key', $key);
    }
}
