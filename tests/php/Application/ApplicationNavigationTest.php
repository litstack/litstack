<?php

namespace Tests\Application;

use Fjord\Application\Navigation\Navigation;
use FjordTest\BackendTestCase;

class ApplicationNavigationTest extends BackendTestCase
{
    /** @test */
    public function test_title_method()
    {
        $navigation = new Navigation;
        $this->assertEquals(['title' => 'foo', 'type' => 'title'], $navigation->title('foo'));
    }

    /** @test */
    public function test_group_method()
    {
        $navigation = new Navigation;
        $this->assertEquals(['title' => 'foo', 'type' => 'group', 'children' => ['child' => 'bar']], $navigation->group(['title' => 'foo'], ['child' => 'bar']));
    }

    /** @test */
    public function test_section_method()
    {
        $navigation = new Navigation;
        $this->assertEquals([['foo', 'bar']], $navigation->section(['foo', 'bar'])->toArray());
    }
}
