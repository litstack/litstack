<?php

namespace FjordTest\Crud;

use FjordTest\BackendTestCase;
use Fjord\Support\Facades\Form;

class FormTest extends BackendTestCase
{
    /** @test */
    public function it_registeres_extension_for_one_field()
    {
        Form::extendField(DummyFormFieldExtension::class, DummyExtensionField::class);

        $extensions = Form::getFieldExtensions(new DummyExtensionField);
        $this->assertCount(1, $extensions);
        $this->assertEquals([DummyFormFieldExtension::class], $extensions);
    }

    /** @test */
    public function it_registeres_extension_for_multiple_fields()
    {
        Form::extendField(DummyFormFieldExtension::class, [
            DummyExtensionField::class,
            AnotherDummyExtensionField::class,
        ]);

        $extensions = Form::getFieldExtensions(new DummyExtensionField);
        $this->assertCount(1, $extensions);
        $this->assertEquals([DummyFormFieldExtension::class], $extensions);

        $extensions = Form::getFieldExtensions(new AnotherDummyExtensionField);
        $this->assertCount(1, $extensions);
        $this->assertEquals([DummyFormFieldExtension::class], $extensions);
    }

    /** @test */
    public function it_doesnt_find_extensions_if_there_are_none()
    {
        Form::extendField(DummyFormFieldExtension::class, DummyExtensionField::class);

        $extensions = Form::getFieldExtensions(new AnotherDummyExtensionField);
        $this->assertCount(0, $extensions);
    }

    /** @test */
    public function it_finds_extensions_for_nested_fields()
    {
        Form::extendField(DummyFormFieldExtension::class, NestedDummyExtensionField::class);

        $extensions = Form::getFieldExtensions(new NestedDummyExtensionField);
        $this->assertCount(1, $extensions);
        $this->assertEquals([DummyFormFieldExtension::class], $extensions);
    }
}


class DummyExtensionField
{
}

class NestedDummyExtensionField extends DummyExtensionField
{
}

class AnotherDummyExtensionField
{
}


class DummyFormFieldExtension
{
}
