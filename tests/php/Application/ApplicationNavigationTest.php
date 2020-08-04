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

        $this->assertSame(['title' => 'foo', 'type' => 'title'], $navigation->title('foo'));
    }
}
