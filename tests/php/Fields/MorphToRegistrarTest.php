<?php

namespace FjordTest\Fields;

use FjordTest\BackendTestCase;
use Illuminate\Database\Eloquent\Model;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Relations\MorphToRegistrar;

class MorphToRegistrarTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        //$this->field = $this->getField(MorphToRegistrar::class, 'dummy_morph_to_relation', '');
    }

    /** @test */
    public function test_title_method()
    {
        return $this->markTestSkipped(
            'Field slots not done yet.'
        );
        $this->field->title('dummy_title');
        $this->assertArrayHasKey('title', $this->field->getAttributes());
        $this->assertEquals('dummy_title', $this->field->getAttribute('title'));
    }
}


class MorphToRegistrarFieldModel extends Model
{
    public function dummy_morph_to_relation()
    {
        return $this->morphTo(LaravelRelationFieldRelation::class)
            ->orderBy('dummy_order_column', 'desc');
    }
}
