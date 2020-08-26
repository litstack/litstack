<?php

namespace Tests\Commands;

use Tests\BackendTestCase;

class LitControllerCommandTest extends BackendTestCase
{
    /** @test */
    public function it_creates_controller()
    {
        $this->artisan('lit:controller DummyDefaultController');

        $this->assertFileExists(base_path('lit/app/Controllers/DummyDefaultController.php'));
    }

    /** @test */
    public function it_creates_form_controller()
    {
        $this->artisan('lit:controller DummyFormController --form');

        $this->assertFileExists(base_path('lit/app/Controllers/Form/DummyFormController.php'));
        $this->assertInstanceOf(
            \Ignite\Crud\Controllers\FormController::class,
            new \Lit\Controllers\Form\DummyFormController()
        );
    }

    /** @test */
    public function it_creates_crud_controller()
    {
        $this->artisan('lit:controller DummyCrudController --crud');

        $this->assertFileExists(base_path('lit/app/Controllers/Crud/DummyCrudController.php'));
        $this->assertInstanceOf(
            \Ignite\Crud\Controllers\CrudController::class,
            new \Lit\Controllers\Crud\DummyCrudController()
        );
    }
}
