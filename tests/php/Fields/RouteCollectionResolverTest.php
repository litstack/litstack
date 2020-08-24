<?php

namespace Tests\Fields;

use Lit\Crud\Fields\Route\RouteCollection;
use Lit\Crud\Fields\Route\RouteCollectionResolver;
use Tests\BackendTestCase;
use InvalidArgumentException;

class RouteCollectionResolverTest extends BackendTestCase
{
    /** @test */
    public function it_registeres_resolver()
    {
        $resolver = new RouteCollectionResolver;
        $resolver->register('dummy-id', fn () => null);
        $resolvers = $this->getUnaccessibleProperty($resolver, 'resolvers');
        $this->assertArrayHasKey('dummy-id', $resolvers);
    }

    /** @test */
    public function test_resolve_method_returns_route_collection()
    {
        $resolver = new RouteCollectionResolver;
        $resolver->register('dummy-id', fn () => 'resolved');
        $this->assertInstanceOf(RouteCollection::class, $resolver->resolve('dummy-id'));
    }

    /** @test */
    public function test_resolve_method_fails_when_it_cannot_resolve()
    {
        $resolver = new RouteCollectionResolver;
        $this->expectException(InvalidArgumentException::class);
        $resolver->resolve('dummy-id');
    }

    /** @test */
    public function test_resolve_method_passes_route_collection_to_resolver()
    {
        $resolver = new RouteCollectionResolver;
        $resolver->register('dummy-id', function (...$parameters) {
            $this->assertNotEmpty($parameters);
            $this->assertInstanceOf(RouteCollection::class, $parameters[0]);
        });
        $resolver->resolve('dummy-id');
    }
}
