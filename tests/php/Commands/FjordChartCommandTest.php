<?php

namespace FjordTest\Commands;

use FjordTest\BackendTestCase;

class FjordChartCommandTest extends BackendTestCase
{
    /** @test */
    public function it_creates_area_chart_by_default()
    {
        $this->artisan('fjord:chart DummyDefaultChart');

        $this->assertFileExists(base_path('fjord/app/Config/Charts/DummyDefaultChartConfig.php'));
        $this->assertInstanceOf(
            \Fjord\Chart\Config\AreaChartConfig::class,
            new \FjordApp\Config\Charts\DummyDefaultChartConfig
        );
    }

    /** @test */
    public function it_creates_area_chart()
    {
        $this->artisan('fjord:chart DummyAreaChart --area');

        $this->assertFileExists(base_path('fjord/app/Config/Charts/DummyAreaChartConfig.php'));
        $this->assertInstanceOf(
            \Fjord\Chart\Config\AreaChartConfig::class,
            new \FjordApp\Config\Charts\DummyAreaChartConfig
        );
    }

    /** @test */
    public function it_creates_bar_chart()
    {
        $this->artisan('fjord:chart DummyBarChart --bar');

        $this->assertFileExists(base_path('fjord/app/Config/Charts/DummyBarChartConfig.php'));
        $this->assertInstanceOf(
            \Fjord\Chart\Config\BarChartConfig::class,
            new \FjordApp\Config\Charts\DummyBarChartConfig
        );
    }

    /** @test */
    public function it_creates_donut_chart()
    {
        $this->artisan('fjord:chart DummyDonutChart --donut');

        $this->assertFileExists(base_path('fjord/app/Config/Charts/DummyDonutChartConfig.php'));
        $this->assertInstanceOf(
            \Fjord\Chart\Config\DonutChartConfig::class,
            new \FjordApp\Config\Charts\DummyDonutChartConfig
        );
    }

    /** @test */
    public function it_creates_number_chart()
    {
        $this->artisan('fjord:chart DummyNumberChart --number');

        $this->assertFileExists(base_path('fjord/app/Config/Charts/DummyNumberChartConfig.php'));
        $this->assertInstanceOf(
            \Fjord\Chart\Config\NumberChartConfig::class,
            new \FjordApp\Config\Charts\DummyNumberChartConfig
        );
    }

    /** @test */
    public function it_creates_progress_chart()
    {
        $this->artisan('fjord:chart DummyProgressChart --progress');

        $this->assertFileExists(base_path('fjord/app/Config/Charts/DummyProgressChartConfig.php'));
        $this->assertInstanceOf(
            \Fjord\Chart\Config\ProgressChartConfig::class,
            new \FjordApp\Config\Charts\DummyProgressChartConfig
        );
    }
}
