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
        $group = $this->nav->group(['title' => 'foo'], ['child' => 'bar']);

        $this->assertEquals([
            'title'    => 'foo',
            'type'     => 'group',
            'children' => ['child' => 'bar'],
        ], $group->toArray());
    }

    /** @test */
    public function test_group_method_with_method_chaining()
    {
        $group = $this->nav->group('foo')->icon('bar');

        $this->assertEquals([
            'title'    => 'foo',
            'type'     => 'group',
            'children' => [],
            'icon'     => 'bar',
        ], $group->toArray());
    }

    /** @test */
    public function test_entries_can_be_added_to_group_with_method_chaining()
    {
        $entry1 = m::mock(Entry::class);
        $entry1->shouldReceive('toArray')->andReturn('entry1');
        $entry2 = m::mock(Entry::class);
        $entry2->shouldReceive('toArray')->andReturn('entry2');
        $group = $this->nav->group('foo')->child($entry1)->child($entry2);

        $this->assertEquals([
            'title'    => 'foo',
            'type'     => 'group',
            'children' => ['entry1', 'entry2'],
        ], $group->toArray());
    }

    /** @test */
    public function test_section_method()
    {
        $navigation = app(Navigation::class);
        $this->assertEquals(
            [['foo', 'bar']],
            $this->nav->section(['foo', 'bar'])->toArray()
        );
    }
}
