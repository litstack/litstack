<?php

namespace Tests\Fields;

use Lit\Crud\Fields\ListField\ListCollection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

class FieldListCollectionTest extends TestCase
{
    /** @test */
    public function it_sets_depth_when_created_and_setDepth_is_true()
    {
        $collection = new ListCollection([
            [
                'id'        => 1,
                'parent_id' => 0,
            ],
            [
                'id'        => 2,
                'parent_id' => 0,
            ],
        ], true);

        foreach ($collection as $item) {
            $this->assertArrayHasKey('depth', $item);
        }
    }

    /** @test */
    public function test_set_depth_for_array_items()
    {
        $collection = new ListCollection([
            [
                'id'        => 1,
                'parent_id' => 0,
            ],
            [
                'id'        => 2,
                'parent_id' => 1,
            ],
            [
                'id'        => 3,
                'parent_id' => 2,
            ],
        ]);

        $collection->setDepth();

        $this->assertEquals(1, $collection[0]['depth']);
        $this->assertEquals(2, $collection[1]['depth']);
    }

    /** @test */
    public function test_set_depth_for_models()
    {
        $collection = new ListCollection([
            new DummyListModelForCollectionTest([
                'id'        => 1,
                'parent_id' => 0,
            ]),
            new DummyListModelForCollectionTest([
                'id'        => 2,
                'parent_id' => 1,
            ]),
            new DummyListModelForCollectionTest([
                'id'        => 3,
                'parent_id' => 2,
            ]),
        ]);

        $collection->setDepth();

        $this->assertEquals(1, $collection[0]->depth);
        $this->assertEquals(2, $collection[1]->depth);
        $this->assertEquals(3, $collection[2]->depth);
    }

    /** @test */
    public function test_unflattenList_method()
    {
        $item1 = new DummyListModelForCollectionTest([
            'id'        => 1,
            'parent_id' => 0,
        ]);
        $item2 = new DummyListModelForCollectionTest([
            'id'        => 2,
            'parent_id' => 1,
        ]);

        $collection = (new ListCollection([$item1, $item2]))->unflattenList();

        $this->assertCount(1, $collection);
        $this->assertEquals($item1, $collection->first());
        $this->assertTrue($collection->first()->relationLoaded('children'));
        $this->assertEquals($item2, $collection->first()->children->first());
    }

    /** @test */
    public function test_flattenList_method()
    {
        $item1 = new DummyListModelForCollectionTest([
            'id'        => 1,
            'parent_id' => 0,
        ]);
        $item2 = new DummyListModelForCollectionTest([
            'id'        => 2,
            'parent_id' => 1,
        ]);

        $collection = (new ListCollection([$item1, $item2]))->unflattenList()->flattenList();

        $this->assertCount(2, $collection);
        $this->assertEquals($item1, $collection[0]);
        $this->assertEquals($item2, $collection[1]);
    }
}

class DummyListModelForCollectionTest extends Model
{
    protected $fillable = ['id', 'parent_id'];
}
