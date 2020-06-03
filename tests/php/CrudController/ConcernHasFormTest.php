<?php

namespace FjordTest\CrudController;

use Mockery as m;
use Fjord\Crud\Controllers\Concerns\HasForm;
use FjordTest\BackendTestCase;

class ConcernHasFormTest extends BackendTestCase
{
    /** @test */
    public function test_formExists_method()
    {
        $config = m::mock('config');
        $config->shouldReceive('has')->withArgs(['form_name'])->andReturn('result');
        $controller = new HasFormController;
        $controller->config = $config;

        $result = $this->callUnaccessibleMethod($controller, 'formExists', ['form_name']);

        $this->assertEquals('result', $result);
    }

    /** @test */
    public function test_formExists_method_throws_404_on_invalid_string()
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $controller = new HasFormController;
        $result = $this->callUnaccessibleMethod($controller, 'formExists', ['form_name-a-v']);
    }

    /** @test */
    public function test_formExists_method_works_for_nested_forms()
    {
        $form = m::mock('form');
        $form->shouldReceive('hasField')->withArgs(['block_form'])->andReturn('result');

        $config = m::mock('config');
        $config->shouldReceive('has')->withArgs(['base_form'])->andReturn($form);
        $config->base_form = $form;

        $controller = new HasFormController;
        $controller->config = $config;

        $result = $this->callUnaccessibleMethod($controller, 'formExists', ['base_form-block_form']);

        $this->assertEquals('result', $result);
    }

    /** @test */
    public function test_getForm_method()
    {
        $config = m::mock('config');
        $config->shouldReceive('has')->withArgs(['form_name'])->andReturn(true);
        $config->form_name = 'result';
        $controller = new HasFormController;
        $controller->config = $config;

        $result = $this->callUnaccessibleMethod($controller, 'getForm', ['form_name']);

        $this->assertEquals('result', $result);
    }

    /** @test */
    public function test_getForm_method_works_for_nested_forms()
    {
        $form = m::mock('form');
        $form->shouldReceive('findField')->withArgs(['block_form'])->andReturn('result');
        $form->shouldReceive('hasField')->withArgs(['block_form'])->andReturn(true);

        $config = m::mock('config');
        $config->shouldReceive('has')->withArgs(['base_form'])->andReturn($form);
        $config->base_form = $form;

        $controller = new HasFormController;
        $controller->config = $config;

        $result = $this->callUnaccessibleMethod($controller, 'getForm', ['base_form-block_form']);

        $this->assertEquals('result', $result);
    }
}

class HasFormController
{
    use HasForm;
}
