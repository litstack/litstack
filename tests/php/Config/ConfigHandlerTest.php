<?php

namespace Tests\Config;

use Ignite\Config\ConfigHandler;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class ConfigHandlerTest extends TestCase
{
    public function setUp(): void
    {
    }

    public function tearDown(): void
    {
    }

    /** @test */
    public function test_getNamespace_method()
    {
        $config = new ConfigHandler(new DummyConfig());
        $this->assertSame(DummyConfig::class, $config->getNamespace());
    }

    /** @test */
    public function test_is_method()
    {
        $config = new ConfigHandler(new ChildDummyConfig());
        $this->assertTrue($config->is(DummyConfig::class));
        $this->assertTrue($config->is(ChildDummyConfig::class));
        $this->assertFalse($config->is('foo'));
    }

    /** @test */
    public function test_getReflector_method()
    {
        $config = new ConfigHandler(new DummyConfig());
        $reflector = $config->getReflector();
        $this->assertInstanceOf(ReflectionClass::class, $reflector);
        $this->assertSame(DummyConfig::class, $reflector->getName());
    }

    /** @test */
    public function test_getMethodReflector_method()
    {
        $config = new ConfigHandler(new DummyConfig());
        $reflector = $config->getMethodReflector('foo');
        $this->assertInstanceOf(ReflectionMethod::class, $reflector);
        $this->assertSame('foo', $reflector->getName());
    }

    /** @test */
    public function test_methodNeeds_method()
    {
        $config = new ConfigHandler(new DummyConfig());
        $this->assertTrue($config->methodNeeds('bar', DummyConfigDependency::class));
        $this->assertTrue($config->methodNeeds('bar', DummyConfigDependency::class, 0));
        $this->assertTrue($config->methodNeeds('bar', OtherDummyConfigDependency::class, 1));
        $this->assertFalse($config->methodNeeds('bar', OtherDummyConfigDependency::class, 0));
        $this->assertFalse($config->methodNeeds('bar', DummyConfigDependency::class, 1));
    }

    /** @test */
    public function test_get_method()
    {
        $config = new ConfigHandler(new DummyConfig());
        $this->assertEquals(new Collection([
            'foo' => 'bar',
            'baz' => 'foo',
        ]), ($config->get('foo', 'baz')));
    }

    /** @test */
    public function test_getAttribute_method()
    {
        $config = new ConfigHandler(new DummyConfig());
        $this->assertSame('bar', $config->getAttribute('foo'));
    }

    /** @test */
    public function test_getConfig_method()
    {
        $config = new ConfigHandler($instance = new DummyConfig());
        $this->assertSame($instance, $config->getConfig());
    }
}

interface DummyConfigDependency
{
}

interface OtherDummyConfigDependency
{
}

class DummyConfig
{
    public function foo()
    {
        return 'bar';
    }

    public function bar(DummyConfigDependency $dependency1, OtherDummyConfigDependency $dependency2)
    {
        return 'baz';
    }

    public function baz()
    {
        return 'foo';
    }
}

class ChildDummyConfig extends DummyConfig
{
}
