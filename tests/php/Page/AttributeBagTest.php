<?php

namespace Fjord\Test\Page;

use Fjord\Page\Actions\AttributeBag;
use FjordTest\Traits\TestHelpers;
use PHPUnit\Framework\TestCase;

class AttributeBagTest extends TestCase
{
    use TestHelpers;

    /** @test */
    public function test_magic_method_get()
    {
        $attributes = new AttributeBag(['name' => 'lennart']);
        $this->assertSame('lennart', $attributes->name);
    }

    /** @test */
    public function test_has_method()
    {
        $attributes = new AttributeBag(['name' => 'lennart']);
        $this->assertTrue($attributes->has('name'));
        $this->assertFalse($attributes->has('other'));
    }
}
