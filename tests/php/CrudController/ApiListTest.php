<?php

namespace FjordTest\CrudController;

use FjordTest\BackendTestCase;
use Fjord\Crud\Models\FormBlock;
use Fjord\Crud\Models\FormListItem;
use FjordTest\TestSupport\Models\Post;
use FjordTest\Traits\InteractsWithCrud;

/**
 * This test is using the Crud Post.
 * 
 * @see FjordTest\TestSupport\Config\PostConfig
 * @see FjordTest\TestSupport\Models\Post
 */
class ApiListTest extends BackendTestCase
{
    use InteractsWithCrud;

    public function setUp(): void
    {
        parent::setUp();

        $this->post = Post::create([]);
        $this->actingAs($this->admin, 'fjord');
    }

    // Store
    /** @test */
    public function it_can_store_list_item()
    {
        $this->assertEquals(0, FormListItem::count());
        $url = $this->getCrudRoute("/{$this->post->id}/show/list/test_list");
        $request = $this->post($url)->assertStatus(200);
        $this->assertEquals(1, FormListItem::count());

        $listItem = $this->post->test_list()->first();
        $this->assertEquals('test_list', $listItem->field_id);
        $this->assertEquals(0, $listItem->parent_id);
        $this->assertEquals(0, $listItem->order_column);
    }

    // Store
    /** @test */
    public function it_can_store_multiple_list_item_and_add_order_column()
    {
        $this->assertEquals(0, FormListItem::count());
        $url = $this->getCrudRoute("/{$this->post->id}/show/list/test_list");
        $request = $this->post($url)->assertStatus(200);
        $request = $this->post($url)->assertStatus(200);
        $this->assertEquals(2, FormListItem::count());

        $listItem = $this->post->test_list->last();
        $this->assertEquals(1, $listItem->order_column);
    }

    // Store
    /** @test */
    public function it_cannot_store_for_not_existing_parent_id()
    {
        $this->assertEquals(0, FormListItem::count());
        $url = $this->getCrudRoute("/{$this->post->id}/show/list/test_list");
        $request = $this->post($url, [
            'parent_id' => 10
        ])->assertStatus(404);
    }

    // Store
    /** @test */
    public function it_can_store_for_existing_parent_id()
    {
        $this->assertEquals(0, FormListItem::count());
        $parent = $this->createListItem();
        $url = $this->getCrudRoute("/{$this->post->id}/show/list/test_list");
        $request = $this->post($url, [
            'parent_id' => $parent->id
        ])->assertStatus(200);
    }

    // Destroy
    /** @test */
    public function it_can_destroy_list_item()
    {
        $listItem1 = $this->createListItem();
        $listItem2 = $this->createListItem();
        $this->assertEquals(2, FormListItem::count());

        $url = $this->getCrudRoute("/{$this->post->id}/show/list/test_list/{$listItem2->id}");
        $request = $this->delete($url)->assertStatus(200);
        $this->assertFalse($this->post->test_list()->where('id', $listItem2->id)->exists());
    }

    // Load
    /** @test */
    public function it_can_load_all_list_items()
    {
        $listItem1 = $this->createListItem();
        $listItem2 = $this->createListItem();
        $this->assertEquals(2, FormListItem::count());

        $url = $this->getCrudRoute("/{$this->post->id}/show/list/test_list");

        $request = $this->get($url)->assertStatus(200);
    }

    // Load
    /** @test */
    public function it_can_load_single_list_item()
    {
        $listItem1 = $this->createListItem();
        $listItem2 = $this->createListItem();
        $this->assertEquals(2, FormListItem::count());

        $url = $this->getCrudRoute("/{$this->post->id}/show/list/test_list/{$listItem1->id}");
        $request = $this->get($url)->assertStatus(200);

        $url = $this->getCrudRoute("/{$this->post->id}/show/list/test_list/{$listItem2->id}");
        $request = $this->get($url)->assertStatus(200);
    }

    // Update
    /** @test */
    public function it_can_update_list_item()
    {
        // Creating 2 block.
        $listItem = $this->createListItem();
        $this->assertEquals(1, FormListItem::count());

        // Update block.
        $url = $this->getCrudRoute("/{$this->post->id}/show/list/test_list/{$listItem->id}");
        $request = $this->put($url, [
            'test_list_input' => 'some text'
        ]);
        $request->assertStatus(200);
        $listItem = $this->post->test_list()->first();

        $this->assertEquals('some text', $listItem->test_list_input);
    }

    public function createListItem()
    {
        return FormListItem::create([
            'config_type' => \FjordApp\Config\Crud\PostConfig::class,
            'model_type' => get_class($this->post),
            'model_id' => $this->post->id,
            'field_id' => 'test_list',
            'order_column' => $this->post->test_list()->count()
        ]);
    }
}
