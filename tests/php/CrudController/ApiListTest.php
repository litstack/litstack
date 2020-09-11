<?php

namespace Tests\CrudController;

use Ignite\Crud\Models\ListItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\AssertableJsonString;
use Tests\BackendTestCase;
use Tests\TestSupport\Models\Post;
use Tests\Traits\InteractsWithCrud;

/**
 * This test is using the Crud Post.
 *
 * @see Tests\TestSupport\Config\PostConfig
 * @see Tests\TestSupport\Models\Post
 */
class ApiListTest extends BackendTestCase
{
    use InteractsWithCrud, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->post = Post::create([]);
        $this->actingAs($this->admin, 'lit');
    }

    // Store

    /** @test */
    public function it_can_store_list_item()
    {
        $this->assertEquals(0, ListItem::count());
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/list/store");

        $result = $this->post($url, [
            'field_id' => 'test_list',
            'payload'  => [
                'test_list_input' => 'some text',
            ],
        ])->assertStatus(200)->decodeResponseJson();
        $this->assertEquals(1, ListItem::count());

        $listItem = $this->post->test_list()->first();
        $this->assertEquals('test_list', $listItem->field_id);
        $this->assertEquals(0, $listItem->parent_id);
        $this->assertEquals(0, $listItem->order_column);
        $this->assertEquals($listItem->id, $result['attributes']['id']);
    }

    // Store

    /** @test */
    public function it_can_store_multiple_list_item_and_automatically_adds_order_column()
    {
        $this->assertEquals(0, ListItem::count());
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/list/store");
        $params = [
            'field_id' => 'test_list',
            'payload'  => [
                'test_list_input' => 'some text',
            ],
        ];
        $result = $this->post($url, $params)->assertStatus(200);
        $result = $this->post($url, $params)->assertStatus(200);
        $this->assertEquals(2, ListItem::count());

        $listItem = $this->post->test_list->last();
        $this->assertEquals(1, $listItem->order_column);
    }

    // Store

    /** @test */
    public function it_cannot_store_for_not_existing_parent_id()
    {
        $this->assertEquals(0, ListItem::count());
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/list/store");
        $request = $this->json('POST', $url)->assertStatus(404);
    }

    // Store

    /** @test */
    public function it_can_store_for_existing_parent_id()
    {
        $this->assertEquals(0, ListItem::count());
        $parent = $this->createListItem();
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/list/store");
        $request = $this->post($url, [
            'field_id'  => 'test_list',
            'parent_id' => $parent->id,
        ])->assertStatus(200);
    }

    // Destroy

    /** @test */
    public function it_can_destroy_list_item()
    {
        $listItem1 = $this->createListItem();
        $listItem2 = $this->createListItem();
        $this->assertEquals(2, ListItem::count());

        $url = $this->getCrudRoute("/{$this->post->id}/api/show/list/destroy");
        $request = $this->delete($url, [
            'field_id' => 'test_list',
            'payload'  => [
                'list_item_id' => $listItem2->id,
            ],
        ])->assertStatus(200);
        $this->assertFalse($this->post->test_list()->where('id', $listItem2->id)->exists());
    }

    // Load

    /** @test */
    public function it_can_load_all_list_items()
    {
        $listItem1 = $this->createListItem();
        $listItem2 = $this->createListItem();
        $this->assertEquals(2, ListItem::count());

        $url = $this->getCrudRoute("/{$this->post->id}/api/show/list/index");

        $result = $this->post($url, ['field_id' => 'test_list'])->assertStatus(200)->decodeResponseJson();

        if ($result instanceof AssertableJsonString) {
            $result = $result->json();
        }

        $this->assertCount(2, $result);
        $this->assertEquals($listItem1->id, $result[0]['attributes']['id']);
        $this->assertEquals($listItem2->id, $result[1]['attributes']['id']);
    }

    // Update

    /** @test */
    public function it_can_update_list_item()
    {
        // Creating 2 block.
        $listItem = $this->createListItem();
        $this->assertEquals(1, ListItem::count());

        // Update listItem.
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/list");
        $this->put($url, [
            'field_id'     => 'test_list',
            'list_item_id' => $listItem->id,
            'payload'      => [
                'test_list_input' => 'some text',
            ],
        ])->assertStatus(200);

        $listItem = $this->post->test_list()->first();

        $this->assertEquals('some text', $listItem->test_list_input);
    }

    // Order

    /** @test */
    public function it_can_order_list_items()
    {
        $listItem1 = $this->createListItem();
        $listItem2 = $this->createListItem();
        $listItem3 = $this->createListItem();

        $url = $this->getCrudRoute("/{$this->post->id}/api/show/list/order");
        $r = $this->put($url, [
            'field_id' => 'test_list',
            'payload'  => [
                'items' => [
                    ['id' => $listItem1->id, 'order_column' => 2],
                    ['id' => $listItem2->id, 'order_column' => 3],
                    ['id' => $listItem3->id, 'order_column' => 1],
                ],
            ],
        ])->assertStatus(200);

        $listItems = $this->post->test_list()->orderBy('order_column')->get();
        $this->assertCount(3, $listItems);
        $this->assertEquals($listItem3->id, $listItems[0]->id);
        $this->assertEquals($listItem1->id, $listItems[1]->id);
        $this->assertEquals($listItem2->id, $listItems[2]->id);
    }

    // Order

    /** @test */
    public function it_can_order_list_items_and_update_parent_ids()
    {
        $listItem1 = $this->createListItem();
        $listItem2 = $this->createListItem();
        $listItem3 = $this->createListItem();

        $url = $this->getCrudRoute("/{$this->post->id}/api/show/list/order");
        $this->put($url, [
            'field_id' => 'test_list',
            'payload'  => [
                'items' => [
                    ['id' => $listItem1->id, 'order_column' => 1],
                    ['id' => $listItem2->id, 'order_column' => 1, 'parent_id' => $listItem1->id],
                    ['id' => $listItem3->id, 'order_column' => 1, 'parent_id' => $listItem2->id],
                ],
            ],
        ])->assertStatus(200);

        $listItems = $this->post->test_list()->orderBy('id')->get();

        $this->assertCount(3, $listItems);
        $this->assertEquals(0, $listItems[0]->parent_id);
        $this->assertEquals(1, $listItems[1]->parent_id);
        $this->assertEquals(2, $listItems[2]->parent_id);
    }

    // Order

    /** @test */
    public function it_fails_to_order_when_max_depth_is_reached()
    {
        $listItem1 = $this->createListItem();
        $listItem2 = $this->createListItem();
        $listItem3 = $this->createListItem();
        $listItem4 = $this->createListItem();

        $url = $this->getCrudRoute("/{$this->post->id}/show/list/test_list/order");
        $this->put($url, [
            'field_id' => 'test_list',
            'payload'  => [
                'items' => [
                    ['id' => $listItem1->id, 'order_column' => 1],
                    ['id' => $listItem2->id, 'order_column' => 1, 'parent_id' => $listItem1->id],
                    ['id' => $listItem3->id, 'order_column' => 1, 'parent_id' => $listItem2->id],
                    ['id' => $listItem4->id, 'order_column' => 1, 'parent_id' => $listItem3->id],
                ],
            ],
        ])->assertStatus(405);
    }

    public function createListItem($parent_id = 0)
    {
        return ListItem::create([
            'config_type'  => \Lit\Config\Crud\PostConfig::class,
            'model_type'   => get_class($this->post),
            'model_id'     => $this->post->id,
            'field_id'     => 'test_list',
            'parent_id'    => $parent_id,
            'order_column' => $this->post->test_list()->count(),
        ]);
    }
}
