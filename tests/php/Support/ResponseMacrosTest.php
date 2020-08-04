<?php

namespace Fjord\Test\Support;

use Fjord\Support\Macros\ResponseMacros;
use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ResponseMacrosTest extends TestCase
{
    public function setUp(): void
    {
        (new ResponseMacros)->register();
        $app = new Application;
        $app[ViewFactoryContract::class] = m::mock(ViewFactory::class);
        $app['redirect'] = m::mock(Redirector::class);
        Container::setInstance($app);
    }

    /** @test */
    public function test_success_method()
    {
        $response = response()->success('hello world');
        $data = $response->getData();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('message', $data);
        $this->assertObjectHasAttribute('variant', $data);
        $this->assertSame('hello world', $data->message);
        $this->assertSame('success', $data->variant);
    }

    /** @test */
    public function test_info_method()
    {
        $response = response()->info('hello there');
        $data = $response->getData();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('message', $data);
        $this->assertObjectHasAttribute('variant', $data);
        $this->assertSame('hello there', $data->message);
        $this->assertSame('info', $data->variant);
    }

    /** @test */
    public function test_warning_method()
    {
        $response = response()->warning('oh no');
        $data = $response->getData();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('message', $data);
        $this->assertObjectHasAttribute('variant', $data);
        $this->assertSame('oh no', $data->message);
        $this->assertSame('warning', $data->variant);
    }

    /** @test */
    public function test_danger_method()
    {
        $response = response()->danger('whooops');
        $data = $response->getData();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(405, $response->getStatusCode());
        $this->assertObjectHasAttribute('message', $data);
        $this->assertObjectHasAttribute('variant', $data);
        $this->assertSame('whooops', $data->message);
        $this->assertSame('danger', $data->variant);
    }
}
