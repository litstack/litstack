<?php

namespace Tests\Chart;

use Ignite\Chart\Config\AreaChartConfig;
use Ignite\Chart\Engine\ApexAreaEngine;
use Ignite\Chart\Loader\AreaLoader;
use Ignite\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Tests\BackendTestCase;

class AreaLoaderTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function tearDown(): void
    {
        Schema::drop('posts');
        parent::tearDown();
    }

    /** @test */
    public function test_last24hours_includes_current_hour()
    {
        $config = Config::get(DummyAreaLoaderPostConfig::class);
        $engine = new ApexAreaEngine;
        $chart = new AreaLoader($config, $engine);

        // Setting now to {current_hour}:55
        $now = now()->startOfHour()->addMinutes(55);

        // Creating 2 posts now.
        Carbon::setTestNow($now);
        DummyAreaLoaderPost::create();
        DummyAreaLoaderPost::create();

        // Creating 1 Post 1 hour ago.
        Carbon::setTestNow((clone $now)->subHour());
        DummyAreaLoaderPost::create();

        Carbon::setTestNow($now);
        $result = $chart->get('last24hours');
        $this->assertSame(2, $result['chart']['series'][0]['data'][23]);
        $this->assertSame(1, $result['chart']['series'][0]['data'][22]);

        Carbon::setTestNow($now->addMinutes(10));
        $result = $chart->get('last24hours');
        $this->assertSame(0, $result['chart']['series'][0]['data'][23]);
        $this->assertSame(2, $result['chart']['series'][0]['data'][22]);
    }
}

class DummyAreaLoaderPost extends Model
{
    public $table = 'posts';
}

class DummyAreaLoaderPostConfig extends AreaChartConfig
{
    public $model = DummyAreaLoaderPost::class;

    public function title(): string
    {
    }

    public function value($query)
    {
        return $this->count($query);
    }
}
