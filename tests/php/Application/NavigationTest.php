<?php

namespace Tests\Application;

use Ignite\Application\Navigation\Entry;
use Ignite\Application\Navigation\Navigation;
use Ignite\Application\Navigation\PresetFactory;
use Ignite\Application\Navigation\Title;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class NavigationTest extends TestCase
{
    public function setUp(): void
    {
        $this->preset = m::mock(PresetFactory::class);
        $this->nav = new Navigation($this->preset);
    }

    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function test_title_method()
    {
        $title = $this->nav->title('foo');

        $this->assertInstanceOf(Title::class, $title);

        $this->assertEquals([
            'title' => 'foo',
            'type'  => 'title',
        ], $title->toArray());
    }

    /** @test */
    public function test_entry_method()
    {
        $entry = $this->nav->entry('foo');

        $this->assertInstanceOf(Entry::class, $entry);

        $this->assertEquals([
            'title' => 'foo',
            'type'  => 'entry',
        ], $entry->toArray());
    }

    /** @test */
    public function test_entry_with_params()
    {
        $entry = $this->nav->entry('foo', ['icon' => 'icon']);

        $this->assertEquals([
            'title' => 'foo',
            'icon'  => 'icon',
            'type'  => 'entry',
        ], $entry->toArray());
    }

    /** @test */
    public function test_entry_with_method_chaining()
    {
        $entry = $this->nav->entry('foo')->icon('icon');

        $this->assertEquals([
            'title' => 'foo',
            'icon'  => 'icon',
            'type'  => 'entry',
        ], $entry->toArray());
    }

    /** @test */
    public function test_group_method()
    {
        $this->assertEquals(['title' => 'foo', 'type' => 'group', 'children' => ['child' => 'bar']], $this->nav->group(['title' => 'foo'], ['child' => 'bar']));
    }

    /** @test */
    public function test_section_method()
    {
        $navigation = app(Navigation::class);
        $this->assertEquals([['foo', 'bar']], $this->nav->section(['foo', 'bar'])->toArray());
    }
}
