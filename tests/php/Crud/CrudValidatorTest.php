<?php

namespace FjordTest\Crud;

use Fjord\Crud\CrudValidator;
use Mockery as m;
use FjordTest\BackendTestCase;

class CrudValidatorTest extends BackendTestCase
{
    /** @test */
    public function it_returns_correct_attribute_names()
    {
        $form = m::mock('form');
        $field = m::mock('field');

        $form->shouldReceive('getRegisteredFields')->andReturn(collect([$field]));
        $field->local_key = 'dummy_field';
        $field->translatable = false;
        $field->shouldReceive('getTitle')->andReturn('Dummy Title');

        $names = $this->callUnaccessibleMethod(CrudValidator::class, 'getValidationAttributeNames', [$form]);

        $this->assertArrayHasKey('dummy_field', $names);
        $this->assertEquals('Dummy Title', $names['dummy_field']);
    }

    /** @test */
    public function it_returns_correct_attribute_names_for_translated_fields()
    {
        $form = m::mock('form');
        $field = m::mock('field');

        $form->shouldReceive('getRegisteredFields')->andReturn(collect([$field]));
        $field->local_key = 'dummy_field';
        $field->translatable = true;
        $field->shouldReceive('getTitle')->andReturn('Dummy Title');

        // Test for locales: de, en
        $this->app['config']->set('translatable.locales', ['de', 'en']);

        $names = $this->callUnaccessibleMethod(CrudValidator::class, 'getValidationAttributeNames', [$form]);

        $this->assertArrayHasKey('de.dummy_field', $names);
        $this->assertArrayHasKey('en.dummy_field', $names);
        $this->assertEquals('Dummy Title (de)', $names['de.dummy_field']);
        $this->assertEquals('Dummy Title (en)', $names['en.dummy_field']);
    }

    /** @test */
    public function test_validate()
    {
        $request = m::mock('request');
        $form = m::mock('form');

        $form->shouldReceive('getRules')->withArgs([$request])->andReturn(['dummy rule', 'other dummy rule']);
        $form->shouldReceive('getRegisteredFields')->andReturn([]);
        $request->shouldReceive('validate')->withArgs([
            ['dummy rule', 'other dummy rule'],
            __f('validation'),
            []
        ]);

        CrudValidator::validate($request, $form);
    }
}
