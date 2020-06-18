<?php

namespace FjordTest\Commands;

use FjordTest\BackendTestCase;

class FjordControllerCommandTest extends BackendTestCase
{
    /** @test */
    public function it_creates_controller()
    {
        $this->artisan('fjord:controller DummyDefaultController');

        $this->assertFileExists(base_path('fjord/app/Controllers/DummyDefaultController.php'));
    }

    /** @test */
    public function it_creates_form_controller()
    {
        $this->artisan('fjord:controller DummyFormController --form');

        $this->assertFileExists(base_path('fjord/app/Controllers/Form/DummyFormController.php'));
        $this->assertInstanceOf(
            \Fjord\Crud\Controllers\FormController::class,
            new \FjordApp\Controllers\Form\DummyFormController
        );
    }

    /** @test */
    public function it_creates_crud_controller()
    {
        $this->artisan('fjord:controller DummyCrudController --crud');

        $this->assertFileExists(base_path('fjord/app/Controllers/Crud/DummyCrudController.php'));
        $this->assertInstanceOf(
            \Fjord\Crud\Controllers\CrudController::class,
            new \FjordApp\Controllers\Crud\DummyCrudController
        );
    }
}
