<?php

namespace FjordTest\CrudController;

use Mockery as m;
use Fjord\Crud\BaseForm;
use FjordTest\BackendTestCase;
use FjordTest\TestSupport\Models\Post;
use Fjord\Crud\Controllers\Concerns\ManagesForm;

class ConcernManagesFormTest extends BackendTestCase
{
    /** @test */
    public function test_isSubForm_method()
    {
        $controller = new HasFormController;
        $this->assertFalse($this->callUnaccessibleMethod($controller, 'isSubForm', ['show']));
        $this->assertTrue($this->callUnaccessibleMethod($controller, 'isSubForm', ['form-something']));
        $this->assertTrue($this->callUnaccessibleMethod($controller, 'isSubForm', ['form-something-else']));
    }

    /** @test */
    public function test_fieldHasForm_method()
    {
        $controller = new HasFormController;

        $form = new HasFormDummyForm(HasFormDummyModel::class);
        $this->assertFalse($this->callUnaccessibleMethod($controller, 'fieldHasForm', [$form->input('')]));
        $form = new HasFormDummyForm(HasFormDummyModel::class);
        $this->assertTrue($this->callUnaccessibleMethod($controller, 'fieldHasForm', [$form->blocks('')]));
        $form = new HasFormDummyForm(HasFormDummyModel::class);
        $this->assertTrue($this->callUnaccessibleMethod($controller, 'fieldHasForm', [$form->modal('')]));
    }

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
    public function test_formExists_method_works_for_nested_forms()
    {
        $form = new HasFormDummyForm(HasFormDummyModel::class);
        $form->input('field_without_form')->title('');
        $form->blocks('block_form')->repeatables(function ($rep) {
            $rep->add('text', function () {
            });
        });

        $config = m::mock('config');
        $config->shouldReceive('has')->withArgs(['base_form'])->andReturn($form);
        $config->base_form = $form;

        $controller = new HasFormController;
        $controller->config = $config;

        $this->assertTrue($this->callUnaccessibleMethod($controller, 'formExists', ['base_form-block_form-text']));
        $this->assertFalse($this->callUnaccessibleMethod($controller, 'formExists', ['base_form-other_form']));
        $this->assertFalse($this->callUnaccessibleMethod($controller, 'formExists', ['base_form-field_without_form']));
    }

    /** @test */
    public function test_formExists_method_works_for_nested_forms_check_for_modal_field()
    {
        $form = new HasFormDummyForm(HasFormDummyModel::class);
        $form->modal('modal_form')->form(function () {
        });

        $config = m::mock('config');
        $config->shouldReceive('has')->withArgs(['base_form'])->andReturn($form);
        $config->base_form = $form;

        $controller = new HasFormController;
        $controller->config = $config;

        $this->assertTrue($this->callUnaccessibleMethod($controller, 'formExists', ['base_form-modal_form']));
        $this->assertFalse($this->callUnaccessibleMethod($controller, 'formExists', ['base_form-other_form']));
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
        $form = new HasFormDummyForm(HasFormDummyModel::class);
        $form->input('field_without_form')->title('');
        $form->blocks('block_form')->repeatables(function ($rep) {
            $rep->add('text', function () {
            });
        });

        $config = m::mock('config');
        $config->shouldReceive('has')->withArgs(['base_form'])->andReturn($form);
        $config->base_form = $form;

        $controller = new HasFormController;
        $controller->config = $config;

        $form = $this->callUnaccessibleMethod($controller, 'getForm', ['base_form-block_form-text']);
        $this->assertInstanceOf(BaseForm::class, $form);

        $form = $this->callUnaccessibleMethod($controller, 'getForm', ['base_form-block_form']);
        $this->assertNull($form);

        $form = $this->callUnaccessibleMethod($controller, 'getForm', ['base_form-field_without_form']);
        $this->assertNull($form);
    }

    /** @test */
    public function test_getForm_method_works_for_nested_forms_check_for_modal_field()
    {
        $form = new HasFormDummyForm(HasFormDummyModel::class);
        $form->modal('modal_form')->form(function () {
        });

        $config = m::mock('config');
        $config->shouldReceive('has')->withArgs(['base_form'])->andReturn($form);
        $config->base_form = $form;

        $controller = new HasFormController;
        $controller->config = $config;

        $form = $this->callUnaccessibleMethod($controller, 'getForm', ['base_form-modal_form']);
        $this->assertInstanceOf(BaseForm::class, $form);
    }
}

class HasFormDummyModel
{
}

class HasFormDummyForm extends BaseForm
{
}

class HasFormController
{
    use ManagesForm;

    public function query()
    {
        return Post::query();
    }
}
