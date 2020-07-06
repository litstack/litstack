<?php

namespace FjordTest\Fields;

use Fjord\Crud\Fields\Relations\ManyRelationField;
use Fjord\Exceptions\Traceable\InvalidArgumentException;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithConfig;
use FjordTest\Traits\InteractsWithFields;
use Illuminate\Database\Eloquent\Model;

class ManyRelationFieldTest extends BackendTestCase
{
    use InteractsWithFields;
    use InteractsWithConfig;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(
            DummyManyRelationFieldTest::class,
            'dummy_relation',
            ManyRelationFieldModel::class
        );
    }

    public function getConfig()
    {
        return new ManyRelationFieldRelationConfig();
    }

    /** @test */
    public function test_sortable_method_throws_exception_when_orderBy_is_not_set()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->field->sortable();
    }

    /** @test */
    public function test_sortable_method_throws_exception_when_model_is_not_set()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->setUnaccessibleProperty($this->field, 'relatedModelClass', null);

        $this->field->sortable();
    }

    /** @test */
    public function test_sortable_method()
    {
        $this->setUnaccessibleProperty($this->field, 'previewModifier', function ($query) {
            $query->orderBy('dummy_order_column');
        });
        $this->field->sortable();
        $this->assertTrue($this->field->getAttribute('sortable'));

        $this->field->sortable(false);
        $this->assertFalse($this->field->getAttribute('sortable'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->sortable());
    }

    /** @test */
    public function test_showTableHead_method()
    {
        $this->field->showTableHead();
        $this->assertTrue($this->field->getAttribute('showTableHead'));

        $this->field->showTableHead(false);
        $this->assertFalse($this->field->getAttribute('showTableHead'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->showTableHead());
    }

    /** @test */
    public function test_searchable_method()
    {
        $this->field->searchable();
        $this->assertTrue($this->field->getAttribute('searchable'));

        $this->field->searchable(false);
        $this->assertFalse($this->field->getAttribute('searchable'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->searchable());
    }

    /** @test */
    public function test_perPage_method()
    {
        $this->field->perPage(25);
        $this->assertEquals(25, $this->field->getAttribute('perPage'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->perPage(25));
    }

    /** @test */
    public function test_tagVariant_method()
    {
        $this->field->tagVariant('secondary');
        $this->assertEquals('secondary', $this->field->getAttribute('tagVariant'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->tagVariant('something'));
    }
}

class ManyRelationFieldRelationConfig
{
    public $names = ['singular' => ''];
    public $search = '';
    public $route_prefix = '';
    public $index = '';
    public $model = ManyRelationFieldRelation::class;
}

class ManyRelationFieldRelation extends Model
{
}

class ManyRelationFieldModel extends Model
{
    public function dummy_relation()
    {
        return $this->hasOne(ManyRelationFieldRelation::class);
    }
}

class DummyManyRelationFieldTest extends ManyRelationField
{
}
