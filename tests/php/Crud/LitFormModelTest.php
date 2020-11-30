<?php

namespace Tests\Crud;

use Ignite\Crud\CrudResource;
use Tests\BackendTestCase;
use Tests\Crud\Fixtures\DummyLitFormModel;

class LitFormModelTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        DummyLitFormModel::schemaUp();
    }

    public function tearDown(): void
    {
        DummyLitFormModel::schemaDown();
        parent::tearDown();
    }

    /** @test */
    public function test_resource_method_returns_crud_resource()
    {
        $this->assertInstanceOf(CrudResource::class, DummyLitFormModel::create()->resource());
    }
}
