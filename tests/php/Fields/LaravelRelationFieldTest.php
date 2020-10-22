<?php

namespace Tests\Fields;

use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\Fields\Relations\LaravelRelationField;
use Ignite\Page\Table\ColumnBuilder;
use Ignite\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Tests\BackendTestCase;
use Tests\Traits\InteractsWithConfig;
use Tests\Traits\InteractsWithFields;

class LaravelRelationFieldTest extends BackendTestCase
{
    use InteractsWithFields;
    use InteractsWithConfig;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(
            DummyLaravelRelationField::class,
            'dummy_relation',
            LaravelRelationFieldModel::class
        );
    }

    public function getConfig(string $key, ...$params)
    {
        return new LaravelRelationFieldRelationConfig();
    }

    /** @test */
    public function test_getRelatedModelClass_method()
    {
        $this->assertEquals('dummy_relation', $this->field->getRelationName());
    }

    /** @test */
    public function test_getRelationName_method()
    {
        $this->assertEquals(LaravelRelationFieldRelation::class, $this->field->getRelatedModelClass());
    }

    /** @test */
    public function test_query_method_sets_preview_modifier()
    {
        $modifier = function () {
        };
        $this->setUnaccessibleProperty($this->field, 'previewModifier', $modifier);

        $this->assertEquals(
            $modifier,
            $this->getUnaccessibleProperty($this->field, 'previewModifier')
        );
    }

    /** @test */
    public function test_getRelationQuery_calls_preview_modifier(Type $var = null)
    {
        $this->modifierCalled = false;

        $modifier = function () {
            $this->modifierCalled = true;
        };

        $this->field->query($modifier);
        $this->field->getRelationQuery(new LaravelRelationFieldModel());

        $this->assertTrue($this->modifierCalled);
    }

    /** @test */
    public function test_getRelatedInstance_method()
    {
        $result = $this->callUnaccessibleMethod($this->field, 'getRelatedInstance');
        $this->assertEquals(new LaravelRelationFieldRelation(), $result);
    }

    /** @test */
    public function test_modifyQuery_method()
    {
        $this->modifierCalled = false;

        $freshQuery = LaravelRelationFieldRelation::query();
        $modifier = function ($query) use ($freshQuery) {
            $this->modifierCalled = true;
            $this->assertEquals($freshQuery, $query);
        };

        // Asserting that query has not been modified when no modifier has been set.
        $modifiedQuery = $this->callUnaccessibleMethod($this->field, 'modifyQuery', [$freshQuery]);
        $this->assertEquals($freshQuery, $modifiedQuery);

        // Asserting that modifier is called.
        $this->setUnaccessibleProperty($this->field, 'previewModifier', [$modifier]);
        $this->callUnaccessibleMethod($this->field, 'modifyQuery', [$freshQuery]);
        $this->assertTrue($this->modifierCalled);
    }

    /** @test */
    public function it_sets_correct_order_defaults()
    {
        $attributes = $this->field->getAttributes();

        $this->assertArrayHasKey('orderColumn', $attributes);
        $this->assertArrayHasKey('orderDirection', $attributes);
        $this->assertEquals('dummy_order_column', $attributes['orderColumn']);
        $this->assertEquals('desc', $attributes['orderDirection']);
    }

    /** @test */
    public function it_sets_fresh_related_query()
    {
        $query = $this->getUnaccessibleProperty($this->field, 'query');
        $this->assertEquals(LaravelRelationFieldRelation::query(), $query);
    }

    /** @test */
    public function test_filter_receives_relation_query()
    {
        $this->field->filter(function ($query) {
            $this->assertEquals($query, LaravelRelationFieldRelation::query());
        });
    }

    /** @test */
    public function test_small_method()
    {
        $this->field->small();
        $this->assertTrue($this->field->getAttribute('small'));

        $this->field->small(false);
        $this->assertFalse($this->field->getAttribute('small'));
    }

    /** @test */
    public function test_confirm_method()
    {
        $this->field->confirm();
        $this->assertTrue($this->field->getAttribute('confirm'));

        $this->field->confirm(false);
        $this->assertFalse($this->field->getAttribute('confirm'));
    }

    /** @test */
    public function test_cast_method_sets_cast()
    {
        $this->field->cast('foo', 'bar');
        $this->assertEquals(['foo' => 'bar'], $this->field->getCasts());
    }

    /** @test */
    public function test_casts_method_sets_casts()
    {
        $this->field->casts(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $this->field->getCasts());
    }

    /** @test */
    public function test_casts_are_applied_to_raw_query()
    {
        $this->field->cast('foo', 'bar');
        $casts = $this->field->getQuery()->getModel()->getCasts();
        $this->assertArrayHasKey('foo', $casts);
        $this->assertSame('bar', $casts['foo']);
    }

    /** @test */
    public function test_casts_are_applied_to_relation_query()
    {
        $this->field->cast('foo', 'bar');
        $casts = $this->field->getRelationQuery(new LaravelRelationFieldModel)->getModel()->getCasts();
        $this->assertArrayHasKey('foo', $casts);
        $this->assertSame('bar', $casts['foo']);
    }

    /** @test */
    public function test_preview_closure_receives_table_instance()
    {
        $this->field->preview(function ($table) {
            $this->assertInstanceOf(ColumnBuilder::class, $table);
        });
    }

    /** @test */
    public function test_preview_sets_table_instance()
    {
        $this->field->preview(function ($table) {
        });

        $this->assertInstanceOf(ColumnBuilder::class, $this->field->getAttribute('preview'));
    }

    /** @test */
    public function test_preview_sets_itself_as_column_builder_parent()
    {
        $this->field->preview(function ($table) {
        });

        $this->assertSame($this->field, $this->field->getAttribute('preview')->getParent());
    }

    /** @test */
    public function test_use_fails_when_model_is_different()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->field->use(LaravelRelationFieldDummyRelationWithDifferentModelConfig::class);
    }

    /** @test */
    public function test_use_fails_when_config_doesnt_exist()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Couldn't find config " . Foo::class);
        $this->field->use(Foo::class);
    }

    /** @test */
    public function test_use_adds_search_keys()
    {
        $this->field->use(LaravelRelationFieldDummyRelationConfig::class);
        $this->assertEquals(['foo', 'bar'], $this->field->search);
    }

    /** @test */
    public function test_use_adds_column_preview()
    {
        $this->field->use(LaravelRelationFieldDummyRelationConfig::class);
        $relatedConfig = Config::get(LaravelRelationFieldDummyRelationConfig::class);
        $this->assertEquals(
            $relatedConfig->index->getTable()->getBuilder(),
            $this->field->preview
        );
    }
}

class LaravelRelationFieldRelationConfig
{
    public $names = ['singular' => ''];
    public $search = '';
    public $route_prefix = '';
    public $index = '';
    public $model = LaravelRelationFieldRelation::class;
}

class LaravelRelationFieldRelation extends Model
{
}

class LaravelRelationFieldModel extends Model
{
    public function dummy_relation()
    {
        return $this->hasOne(LaravelRelationFieldRelation::class)
            ->orderBy('dummy_order_column', 'desc');
    }
}

class DummyLaravelRelationField extends LaravelRelationField
{
}

class LaravelRelationFieldDummyRelationConfig extends CrudConfig
{
    public $model = LaravelRelationFieldRelation::class;

    public function names()
    {
        return [
            'singular' => 'FooSingular',
            'plural'   => 'FooPlural',
        ];
    }

    public function index(CrudIndex $page)
    {
        $page->table(function ($table) {
            $table->col('Title');
        })->search('foo', 'bar');
    }
}

class LaravelRelationFieldDummyRelationWithDifferentModelConfig extends CrudConfig
{
    public $model = Foo::class;
}
