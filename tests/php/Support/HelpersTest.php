<?php

namespace Tests\Support;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Ignite\Crud\Models\Traits\Translatable;
use Ignite\Foundation\Litstack;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function test_lit_path_helper()
    {
        $app = new Application;
        $litstack = $app['lit'] = m::mock(Litstack::class);
        Container::setInstance($app);
        $litstack->shouldReceive('path')->withArgs(['foo'])->andReturn('result');
        $this->assertSame('result', lit_path('foo'));
    }

    /** @test */
    public function test_lit_base_path_helper()
    {
        $app = new Application;
        $litstack = $app['lit'] = m::mock(Litstack::class);
        Container::setInstance($app);
        $litstack->shouldReceive('basePath')->withArgs(['foo'])->andReturn('result');
        $this->assertSame('result', lit_base_path('foo'));
    }

    /** @test */
    public function test_lit_vendor_path_helper()
    {
        $app = new Application;
        $litstack = $app['lit'] = m::mock(Litstack::class);
        Container::setInstance($app);
        $litstack->shouldReceive('vendorPath')->withArgs(['foo'])->andReturn('result');
        $this->assertSame('result', lit_vendor_path('foo'));
    }

    /** @test */
    public function test_lit_resource_path_helper()
    {
        $app = new Application;
        $litstack = $app['lit'] = m::mock(Litstack::class);
        Container::setInstance($app);
        $litstack->shouldReceive('resourcePath')->withArgs(['foo'])->andReturn('result');
        $this->assertSame('result', lit_resource_path('foo'));
    }

    /** @test */
    public function test_lit_lang_path_helper()
    {
        $app = new Application;
        $litstack = $app['lit'] = m::mock(Litstack::class);
        Container::setInstance($app);
        $litstack->shouldReceive('langPath')->withArgs(['foo'])->andReturn('result');
        $this->assertSame('result', lit_lang_path('foo'));
    }

    /** @test */
    public function test_is_translatale_helper()
    {
        $this->assertFalse(is_translatable(HelpersNonTranslatableModel::class));
        $this->assertFalse(is_translatable(new HelpersNonTranslatableModel()));
        $this->assertTrue(is_translatable(HelpersTranslatableModel::class));
        $this->assertTrue(is_translatable(new HelpersTranslatableModel()));
    }

    /** @test */
    public function test_is_attribute_translatale_helper()
    {
        $this->assertFalse(is_attribute_translatable('some_attribute', HelpersNonTranslatableModel::class));
        $this->assertFalse(is_attribute_translatable('some_attribute', HelpersTranslatableModel::class));
        $this->assertTrue(is_attribute_translatable('translated_attribute', HelpersTranslatableModel::class));
    }
}

class HelpersNonTranslatableModel extends Model
{
}

class HelpersTranslatableModel extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['translated_attribute'];
}
