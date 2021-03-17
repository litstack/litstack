<?php

namespace Tests;

use Ignite\Contracts\Vue\Authorizable as AuthorizableContract;
use Ignite\Support\VueProp;
use Ignite\Vue\Traits\Authorizable;
use PHPUnit\Framework\TestCase;

class VueTraitsTest extends TestCase
{
    /** @test */
    public function vue_prop_renders_to_array()
    {
        $obj = new DummyRenderable;
        $obj->data = 'foo';

        $this->assertSame(['data' => 'foo'], $obj->toArray());
    }

    /** @test */
    public function vue_prop_renders_to_json()
    {
        $obj = new DummyRenderable;
        $obj->data = 'foo';

        $this->assertSame('{"data":"foo"}', $obj->toJson());
    }

    /** @test */
    public function vue_prop_to_string_returns_json()
    {
        $obj = new DummyRenderable;
        $obj->data = 'foo';

        $this->assertSame('{"data":"foo"}', (string) $obj);
    }

    /** @test */
    public function vue_prop_doesnt_filter_empty_array_keys()
    {
        $obj = new DummyRenderable;

        $this->assertSame(['data' => null], $obj->toArray());
    }

    /** @test */
    public function it_filters_unauthorized_objects()
    {
        $obj = new AuthorizableDummyRenderable;
        $obj->data = 'foo';

        $obj->authorize(fn () => false);

        $this->assertSame(['data' => 'foo'], $obj->toArray());
    }

    /** @test */
    public function it_filters_unauthorized_with_boolean_value()
    {
        $obj = new AuthorizableDummyRenderable;
        $obj->data = 'foo';

        $obj->authorize(false);

        $this->assertSame(['data' => 'foo'], $obj->toArray());
    }
}

class DummyRenderable extends VueProp
{
    public $data;

    public function render(): array
    {
        return [
            'data' => $this->data,
        ];
    }
}

class AuthorizableDummyRenderable extends VueProp implements AuthorizableContract
{
    use Authorizable;

    public $data;

    public function render(): array
    {
        return [
            'data' => $this->data,
        ];
    }
}
