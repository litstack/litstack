<?php

namespace Tests\Application;

use Ignite\Application\Navigation\Navigation;
use Tests\BackendTestCase;

class ApplicationNavigationTest extends BackendTestCase
{
    /** @test */
    public function test_title_method()
    {
        $navigation = app(Navigation::class);
        $this->assertEquals(['title' => 'foo', 'type' => 'title'], $navigation->title('foo'));
    }

    /** @test */
    public function test_group_method()
    {
        $navigation = app(Navigation::class);
        $this->assertEquals(['title' => 'foo', 'type' => 'group', 'children' => ['child' => 'bar']], $navigation->group(['title' => 'foo'], ['child' => 'bar']));
    }

    /** @test */
    public function test_section_method()
    {
        $navigation = app(Navigation::class);
        $this->assertEquals([['foo', 'bar']], $navigation->section(['foo', 'bar'])->toArray());
    }
}
