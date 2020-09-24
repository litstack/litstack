<?php

namespace Tests\Crud;

use Ignite\Crud\Crud;
use Ignite\Crud\Fields\Block\Block;
use Ignite\Crud\Fields\Block\Repeatable as BaseRepeatable;
use Ignite\Crud\Fields\Block\RepeatableForm;
use Ignite\Crud\Fields\Block\Repeatables;
use Ignite\Crud\Repeatable;
use Ignite\Page\Table\ColumnBuilder;
use Mockery as m;
use Tests\BackendTestCase;

class CrudTest extends BackendTestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_registers_repeatable()
    {
        $crud = new Crud;
        $crud->repeatable('foo', FooCrudRepeatable::class);

        $field = m::mock(Block::class)->makePartial();

        $rep = new Repeatables($field);
        $this->assertInstanceOf(BaseRepeatable::class, $rep->foo());
    }
}

class FooCrudRepeatable extends Repeatable
{
    public function preview(ColumnBuilder $preview)
    {
        // code...
    }

    public function form(RepeatableForm $form)
    {
    }
}
