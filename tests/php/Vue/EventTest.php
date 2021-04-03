<?php

namespace Tests;

use Ignite\Vue\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    /** @test */
    public function test_getHandler_method()
    {
        $event = new Event('click', 'foo');
        $this->assertSame('foo', $event->getHandler());
    }

    /** @test */
    public function test_getName_method()
    {
        $event = new Event('click', 'foo');
        $this->assertSame('click', $event->getName());
    }

    /** @test */
    public function test_isFileDownload_method()
    {
        $event = new Event('click', 'foo');
        $this->assertSame($event, $event->isFileDownload());
        $this->assertTrue($event->getAttribute('isFileDownload'));
        $event->isFileDownload(false);
        $this->assertFalse($event->getAttribute('isFileDownload'));
    }

    /** @test */
    public function it_renders_name_and_handler()
    {
        $event = new Event('click', 'foo');
        $this->assertEquals([
            'name'    => 'click',
            'handler' => 'foo',
        ], $event->render());
    }
}
