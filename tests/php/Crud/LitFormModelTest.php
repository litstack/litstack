<?php

namespace Tests\Crud;

use Ignite\Crud\CrudShow;
use Tests\BackendTestCase;
use Ignite\Crud\Models\Form;
use Ignite\Crud\CrudResource;
use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\Models\LitFormModel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\Crud\Fixtures\DummyLitFormModel;

class LitFormModelTest extends BackendTestCase
{
    public function setUp():void
    {
        parent::setUp();
        DummyLitFormModel::schemaUp();
    }
    
    public function tearDown():void
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
