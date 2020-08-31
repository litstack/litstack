<?php

namespace Tests\Commands;

use Tests\BackendTestCase;

class LitFormCommandTest extends BackendTestCase
{
    /** @test */
    public function it_creates_form()
    {
        $this->artisan('lit:form --collection=test --form=home');

        $this->assertFileExists(base_path('lit/app/Controllers/Form/Test/HomeController.php'));
        $this->assertFileExists(base_path('lit/app/Config/Form/Test/HomeConfig.php'));
        $this->assertInstanceOf(
            \Ignite\Crud\Config\FormConfig::class,
            new \Lit\Config\Form\Test\HomeConfig()
        );
    }

    /** @test */
    public function it_creates_form_when_name_has_undescore()
    {
        $this->artisan('lit:form --collection=test_collection --form=home_page');

        $this->assertFileExists(base_path('lit/app/Controllers/Form/TestCollection/HomePageController.php'));
        $this->assertFileExists(base_path('lit/app/Config/Form/TestCollection/HomePageConfig.php'));
        $this->assertInstanceOf(
            \Ignite\Crud\Config\FormConfig::class,
            new \Lit\Config\Form\TestCollection\HomePageConfig()
        );
    }

    /** @test */
    public function it_creates_form_when_name_has_minus()
    {
        $this->artisan('lit:form --collection=test-col --form=other-page');

        $this->assertFileExists(base_path('lit/app/Controllers/Form/TestCol/OtherPageController.php'));
        $this->assertFileExists(base_path('lit/app/Config/Form/TestCol/OtherPageConfig.php'));
        $this->assertInstanceOf(
            \Ignite\Crud\Config\FormConfig::class,
            new \Lit\Config\Form\TestCol\OtherPageConfig()
        );
    }
}
