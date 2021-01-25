<?php

namespace Tests\Crud;

use Ignite\Crud\BaseForm;
use Ignite\Crud\CrudShow;
use Ignite\Exceptions\Traceable\InvalidArgumentException;
use Ignite\Support\Vue\ButtonComponent;
use Illuminate\Database\Eloquent\Model;
use Tests\BackendTestCase;

class CrudShowTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $form = new BaseForm(CrudFormDummyModel::class);

        $this->page = new CrudShow($form);
    }

    /** @test */
    public function it_notices_when_form_is_registering_in_card()
    {
        $this->assertFalse($this->page->inCard());
        $this->page->card(function ($page) {
            $this->assertTrue($page->inCard());
            //
        });
        $this->assertFalse($this->page->inCard());
    }

    /** @test */
    public function it_registeres_card_wrapper()
    {
        $this->page->card(function ($page) {
            $this->assertTrue($page->inCard());
        });

        $components = $this->page->getComponents();
        $this->assertCount(1, $components);
        $this->assertEquals('lit-wrapper', $components[0]->getName());
        $this->assertEquals('lit-wrapper-card', $components[0]->wrapperComponent->getName());
    }

    /** @test */
    public function it_sets_different_info_heading_in_card()
    {
        $outsideInfo = $this->page->info('some title');

        $this->page->card(function ($page) use ($outsideInfo) {
            $insideInfo = $this->page->info('some title');

            $this->assertNotEquals($outsideInfo->heading, $insideInfo->heading);
        });
    }

    /** @test */
    public function it_denies_to_register_fields_outside_of_wrappers()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->page->input('dummy-component');
    }

    /** @test */
    public function it_adds_component_when_outside_card()
    {
        $component = $this->page->component('dummy-component');

        $components = $this->page->getComponents();
        $this->assertCount(1, $components);
        $this->assertEquals($component, $components[0]);
    }

    /** @test */
    public function test_preview_returns_button_component()
    {
        $this->assertInstanceOf(ButtonComponent::class, $this->page->preview('foo'));
    }

    /** @test */
    public function test_preview_adds_component_to_headerRight_slot()
    {
        $this->page->preview('foo');
        $this->assertTrue($this->page->headerRight()->hasComponent('b-button'));
    }

    /** @test */
    public function it_calls_event_handlers_when_model_event_is_fired()
    {
        $this->called = false;
        $this->page->on('foo', function () {
            $this->called = true;
        });
        $this->page->fireEvent('foo');
        $this->assertTrue($this->called);
    }

    /** @test */
    public function test_fireEvent_passes_parameters_to_event_handler()
    {
        $this->page->on('foo', function ($var1, $var2) {
            $this->assertEquals('bar', $var1);
            $this->assertEquals('baz', $var2);
        });
        $this->page->fireEvent('foo', ['bar', 'baz']);
        $this->page->fireEvent('foo', 'bar', 'baz');
    }
}

class CrudFormDummyModel extends Model
{
}
