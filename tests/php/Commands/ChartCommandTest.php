<?php

namespace Tests\Commands;

use Tests\BackendTestCase;

class ChartCommandTest extends BackendTestCase
{
    /** @test */
    public function it_creates_area_chart_by_default()
    {
        $this->artisan('lit:chart DummyDefaultChart');

        $this->assertFileExists(base_path('lit/app/Config/Charts/DummyDefaultChartConfig.php'));
        $this->assertInstanceOf(
            \Ignite\Chart\Config\AreaChartConfig::class,
            new \Lit\Config\Charts\DummyDefaultChartConfig()
        );
    }

    /** @test */
    public function it_creates_area_chart()
    {
        $this->artisan('lit:chart DummyAreaChart --area');

        $this->assertFileExists(base_path('lit/app/Config/Charts/DummyAreaChartConfig.php'));
        $this->assertInstanceOf(
            \Ignite\Chart\Config\AreaChartConfig::class,
            new \Lit\Config\Charts\DummyAreaChartConfig()
        );
    }

    /** @test */
    public function it_creates_bar_chart()
    {
        $this->artisan('lit:chart DummyBarChart --bar');

        $this->assertFileExists(base_path('lit/app/Config/Charts/DummyBarChartConfig.php'));
        $this->assertInstanceOf(
            \Ignite\Chart\Config\BarChartConfig::class,
            new \Lit\Config\Charts\DummyBarChartConfig()
        );
    }

    /** @test */
    public function it_creates_donut_chart()
    {
        $this->artisan('lit:chart DummyDonutChart --donut');

        $this->assertFileExists(base_path('lit/app/Config/Charts/DummyDonutChartConfig.php'));
        $this->assertInstanceOf(
            \Ignite\Chart\Config\DonutChartConfig::class,
            new \Lit\Config\Charts\DummyDonutChartConfig()
        );
    }

    /** @test */
    public function it_creates_number_chart()
    {
        $this->artisan('lit:chart DummyNumberChart --number');

        $this->assertFileExists(base_path('lit/app/Config/Charts/DummyNumberChartConfig.php'));
        $this->assertInstanceOf(
            \Ignite\Chart\Config\NumberChartConfig::class,
            new \Lit\Config\Charts\DummyNumberChartConfig()
        );
    }

    /** @test */
    public function it_creates_progress_chart()
    {
        $this->artisan('lit:chart DummyProgressChart --progress');

        $this->assertFileExists(base_path('lit/app/Config/Charts/DummyProgressChartConfig.php'));
        $this->assertInstanceOf(
            \Ignite\Chart\Config\ProgressChartConfig::class,
            new \Lit\Config\Charts\DummyProgressChartConfig()
        );
    }
}
