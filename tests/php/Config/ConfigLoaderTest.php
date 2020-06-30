<?php

namespace FjordTest\Config;

use FjordTest\BackendTestCase;
use Fjord\Support\Facades\Config;

class ConfigLoaderTest extends BackendTestCase
{
    /** @test */
    public function test_getNamespaceFromKey_method()
    {
        $className = Config::getNamespaceFromKey('my.config.key');
        $this->assertEquals('FjordApp\Config\My\Config\KeyConfig', $className);

        $className = Config::getNamespaceFromKey('my.config.snake_key');
        $this->assertEquals('FjordApp\Config\My\Config\SnakeKeyConfig', $className);
    }

    /** @test */
    public function test_getKey_method()
    {
        $key = Config::getKey('FjordApp\Config\My\Config\KeyConfig');
        $this->assertEquals('my.config.key', $key);

        $key = Config::getKey('FjordApp\Config\My\Config\SnakeKeyConfig');
        $this->assertEquals('my.config.snake_key', $key);
    }
}
