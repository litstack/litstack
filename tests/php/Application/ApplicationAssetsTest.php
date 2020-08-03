<?php

namespace Fjord\Test;

use Fjord\Application\Application;
use PHPUnit\Framework\TestCase;

class ApplicationAssetsTest extends TestCase
{
    /** @test */
    public function test_style_can_be_added()
    {
        $application = new Application;
        $this->assertInstanceOf(Application::class, $application->style('path'));
        $this->assertContains('path', $application->getStyles());
    }

    /** @test */
    public function test_script_can_be_added()
    {
        $application = new Application;
        $this->assertInstanceOf(Application::class, $application->script('src'));
        $this->assertContains('src', $application->getScripts());
    }
}
