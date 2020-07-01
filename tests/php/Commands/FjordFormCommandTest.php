<?php

namespace FjordTest\Commands;

use FjordTest\BackendTestCase;

class FjordFormCommandTest extends BackendTestCase
{
    /** @test */
    public function it_creates_form()
    {
        $this->artisan('fjord:form --collection=test --form=home');

        $this->assertFileExists(base_path('fjord/app/Controllers/Form/Test/HomeController.php'));
        $this->assertFileExists(base_path('fjord/app/Config/Form/Test/HomeConfig.php'));
        $this->assertInstanceOf(
            \Fjord\Crud\Config\FormConfig::class,
            new \FjordApp\Config\Form\Pages\HomeConfig()
        );
    }

    /** @test */
    public function it_creates_form_when_name_has_undescore()
    {
        $this->artisan('fjord:form --collection=test_collection --form=home_page');

        $this->assertFileExists(base_path('fjord/app/Controllers/Form/TestCollection/HomePageController.php'));
        $this->assertFileExists(base_path('fjord/app/Config/Form/TestCollection/HomePageConfig.php'));
        $this->assertInstanceOf(
            \Fjord\Crud\Config\FormConfig::class,
            new \FjordApp\Config\Form\TestCollection\HomePageConfig()
        );
    }

    /** @test */
    public function it_creates_form_when_name_has_minus()
    {
        $this->artisan('fjord:form --collection=test-col --form=other-page');

        $this->assertFileExists(base_path('fjord/app/Controllers/Form/TestCol/OtherPageController.php'));
        $this->assertFileExists(base_path('fjord/app/Config/Form/TestCol/OtherPageConfig.php'));
        $this->assertInstanceOf(
            \Fjord\Crud\Config\FormConfig::class,
            new \FjordApp\Config\Form\TestCol\OtherPageConfig()
        );
    }
}
