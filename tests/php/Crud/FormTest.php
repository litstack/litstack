<?php

namespace Tests\Crud;

use Ignite\Support\Facades\Form;
use Tests\BackendTestCase;

class FormTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUnaccessibleProperty(Form::getFacadeRoot(), 'fields', []);
    }

    /** @test */
    public function it_can_register_field()
    {
        Form::registerField('name', 'field');

        $fields = $this->getUnaccessibleProperty(Form::getFacadeRoot(), 'fields');
        $this->assertCount(1, $fields);
        $this->assertArrayHasKey('name', $fields);
        $this->assertEquals('field', $fields['name']);
    }

    /** @test */
    public function test_fieldExists_method()
    {
        $this->setUnaccessibleProperty(Form::getFacadeRoot(), 'fields', ['dummy_field' => 'field']);

        $this->assertTrue(Form::fieldExists('dummy_field'));
        $this->assertFalse(Form::fieldExists('other_field'));
    }

    /** @test */
    public function test_getField_method()
    {
        $this->setUnaccessibleProperty(Form::getFacadeRoot(), 'fields', ['dummy_field' => 'field']);

        $this->assertEquals('field', Form::getField('dummy_field'));
        $this->assertNull(Form::getField('other_field'));
    }
}
