<?php

namespace FjordTest\Crud;

use Fjord\Crud\CrudShow;
use Fjord\Exceptions\InvalidArgumentException;
use FjordTest\BackendTestCase;
use Illuminate\Database\Eloquent\Model;

class CrudShowTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->form = new CrudShow(CrudFormDummyModel::class);
    }

    /** @test */
    public function it_notices_when_form_is_registering_in_card()
    {
        $this->assertFalse($this->form->inCard());
        $this->form->card(function ($form) {
            $this->assertTrue($form->inCard());
            //
        });
        $this->assertFalse($this->form->inCard());
    }

    /** @test */
    public function it_registeres_card_wrapper()
    {
        $this->form->card(function ($form) {
            $this->assertTrue($form->inCard());
        });

        $components = $this->form->getComponents();
        $this->assertCount(1, $components);
        $this->assertEquals('fj-field-wrapper', $components[0]->getName());
        $this->assertEquals('fj-field-wrapper-card', $components[0]->wrapperComponent->getName());
    }

    /** @test */
    public function it_sets_different_info_heading_in_card()
    {
        $outsideInfo = $this->form->info('some title');

        $this->form->card(function ($form) use ($outsideInfo) {
            $insideInfo = $this->form->info('some title');

            $this->assertNotEquals($outsideInfo->heading, $insideInfo->heading);
        });
    }

    /** @test */
    public function it_denies_to_register_fields_outside_of_wrappers()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->form->input('dummy-component');
    }

    /** @test */
    public function it_adds_component_when_outside_card()
    {
        $component = $this->form->component('dummy-component');

        $components = $this->form->getComponents();
        $this->assertCount(1, $components);
        $this->assertEquals($component, $components[0]);
    }

    /** @test */
    public function it_registeres_component_field_when_inside_card()
    {
        $card = $this->form->card(function ($form) {
            $component = $this->form->component('dummy-component');
        });

        $components = $this->form->getComponents();
        $fields = $this->form->getRegisteredFields();
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
