<?php

namespace Tests\Foundation;

use Ignite\Support\Facades\Vue;
use Tests\BackendTestCase;

class ComponentsRegisteredTest extends BackendTestCase
{
    /** @test */
    public function test_lit_info_component_is_registered()
    {
        $this->assertComponentRegistered('lit-info');
    }

    /** @test */
    public function test_lit_blade_component_is_registered()
    {
        $this->assertComponentRegistered('lit-blade');
    }

    /** @test */
    public function test_lit_field_wrapper_component_is_registered()
    {
        $this->assertComponentRegistered('lit-field-wrapper');
    }

    /** @test */
    public function test_lit_field_wrapper_card_component_is_registered()
    {
        $this->assertComponentRegistered('lit-field-wrapper-card');
    }

    /** @test */
    public function test_lit_field_wrapper_group_component_is_registered()
    {
        $this->assertComponentRegistered('lit-field-wrapper-group');
    }

    /** @test */
    public function test_lit_component_is_registered()
    {
        $this->assertComponentRegistered('lit-col-image');
    }

    /** @test */
    public function test_lit_col_toggle_component_is_registered()
    {
        $this->assertComponentRegistered('lit-col-toggle');
    }

    /** @test */
    public function test_lit_col_crud_relation_component_is_registered()
    {
        $this->assertComponentRegistered('lit-col-crud-relation');
    }

    public function assertComponentRegistered($component)
    {
        $this->assertTrue(
            array_key_exists($component, Vue::all()),
            "Failed asserting that vue component {$component} is registered."
        );
    }
}
