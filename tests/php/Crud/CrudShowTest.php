<?php

namespace FjordTest\Crud;

use Fjord\Crud\BaseForm;
use Fjord\Crud\CrudShow;
use Fjord\Exceptions\Traceable\InvalidArgumentException;
use FjordTest\BackendTestCase;
use Illuminate\Database\Eloquent\Model;

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
        $this->assertEquals('fj-field-wrapper', $components[0]->getName());
        $this->assertEquals('fj-field-wrapper-card', $components[0]->wrapperComponent->getName());
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
    public function it_registeres_component_field_when_inside_card()
    {
        $card = $this->page->card(function ($page) {
            $component = $this->page->component('dummy-component');
        });

        $components = $this->page->getComponents();
        $fields = $this->page->getRegisteredFields();

        $this->assertCount(1, $components);
        $this->assertCount(1, $fields);
        $this->assertEquals('fj-field-wrapper', $components[0]->getName());
        $this->assertEquals('fj-field-wrapper-card', $components[0]->wrapperComponent->getName());
        $this->assertInstanceof(\Fjord\Crud\Fields\Component::class, $fields[0]);
    }
}

class CrudFormDummyModel extends Model
{
}
