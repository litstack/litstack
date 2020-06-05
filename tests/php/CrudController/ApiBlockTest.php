<?php

namespace FjordTest\CrudController;

use Fjord\Crud\Models\FormBlock;
use FjordTest\BackendTestCase;
use FjordTest\FrontendTestCase;
use FjordTest\TestSupport\Models\Post;
use FjordTest\Traits\InteractsWithCrud;

/**
 * This test is using the Crud Post.
 * 
 * @see FjordApp\Config\Crud\PostConfig
 * @see FjordTest\TestSupport\Models\Post
 */
class ApiBlockTest extends BackendTestCase
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
    public function it_can_store_block()
    {
        $this->assertCount(0, $this->getBlocks('text'));

        $url = $this->getCrudRoute("/{$this->post->id}/show/blocks/content");
        $request = $this->post($url, ['type' => 'text']);
        $request->assertStatus(200);
        $this->assertCount(1, $this->getBlocks('text'));
        $this->assertCount(1, $this->getBlocks());

        $url = $this->getCrudRoute("/{$this->post->id}/show/blocks/content");
        $request = $this->post($url, ['type' => 'input']);
        $request->assertStatus(200);

        // Asserting 2 blocks at all.
        $this->assertCount(2, $this->getBlocks());
        // And one with type input.
        $this->assertCount(1, $this->getBlocks('input'));
    }

    // Store
    /** @test */
    public function it_cannot_store_block_when_repeatable_does_not_exist()
    {
        $this->assertCount(0, $this->getBlocks('text'));

        $url = $this->getCrudRoute("/{$this->post->id}/show/blocks/content");
        $request = $this->post($url, ['type' => 'other']);
        $request->assertStatus(404);
        $this->assertCount(0, $this->getBlocks());
    }

    // Store
    /** @test */
    public function it_cannot_store_block_for_fields_that_are_not_blocks()
    {
        $this->assertCount(0, $this->getBlocks('text'));

        $url = $this->getCrudRoute("/{$this->post->id}/show/blocks/title");
        $request = $this->post($url, ['type' => 'text']);
        $request->assertStatus(404);
        $this->assertCount(0, $this->getBlocks());
    }

    // Load all
    /** @test */
    public function it_can_load_all_blocks()
    {
        // Creating 2 blocks.
        $this->createBlock();
        $this->createBlock();
        $this->assertCount(2, $this->getBlocks());

        // Send request.
        $url = $this->getCrudRoute("/{$this->post->id}/show/blocks/content");
        $request = $this->json('GET', $url);

        // Assertions.
        $request->assertStatus(200);
        $request->assertJsonCount(2);
    }

    // Load one
    /** @test */
    public function it_can_load_block()
    {
        // Creating block.
        $block = $this->createBlock('text');
        $this->assertCount(1, $this->getBlocks('text'));

        // Send request.
        $url = $this->getCrudRoute("/{$this->post->id}/show/blocks/content/{$block->id}");
        $request = $this->json('GET', $url);

        // Assertions.
        $request->assertStatus(200);
        $request->assertJson(['attributes' => $block->getAttributes()]);
    }

    // Delete
    /** @test */
    public function it_can_delete_block()
    {
        // Creating 2 block.
        $block1 = $this->createBlock('text');
        $block2 = $this->createBlock('text');
        $this->assertCount(2, $this->getBlocks('text'));

        // Delete first block.
        $url = $this->getCrudRoute("/{$this->post->id}/show/blocks/content/{$block1->id}");
        $request = $this->delete($url);
        $request->assertStatus(200);
        $this->assertCount(1, $this->getBlocks('text'));

        // Delete second block.
        $url = $this->getCrudRoute("/{$this->post->id}/show/blocks/content/{$block2->id}");
        $request = $this->delete($url);
        $request->assertStatus(200);
        $this->assertCount(0, $this->getBlocks('text'));
    }

    // Update
    /** @test */
    public function it_can_update_block()
    {
        // Creating 2 block.
        $block = $this->createBlock('text');
        $this->assertCount(1, $this->getBlocks('text'));

        // Update block.
        $url = $this->getCrudRoute("/{$this->post->id}/show/blocks/content/{$block->id}");
        $request = $this->put($url, [
            'text' => 'some text'
        ]);
        $request->assertStatus(200);
        $block = $this->getBlocks('text')->first();
        $this->assertEquals('some text', $block->text);
    }

    // Update
    /** @test */
    public function test_update_validates_request()
    {
        // Creating 2 block.
        $block = $this->createBlock('text');
        $this->assertCount(1, $this->getBlocks('text'));

        // Text uses the rule "min:5"
        $url = $this->getCrudRoute("/{$this->post->id}/show/blocks/content/{$block->id}");
        $request = $this->put($url, [
            'text' => 'ab'
        ]);
        //$request->assertStatus(302);
        $block = $this->getBlocks('text')->first();
        $this->assertEquals('', $block->text);
    }

    public function getBlocks($type = null)
    {
        $query = FormBlock::where('model_type', get_class($this->post))
            ->where('model_id', $this->post->id)
            ->where('field_id', 'content');

        if ($type) {
            $query->where('type', $type);
        }

        return $query->get();
    }

    public function createBlock($type = 'text')
    {
        return FormBlock::create([
            'type' => $type,
            'model_type' => get_class($this->post),
            'model_id' => $this->post->id,
            'field_id' => 'content'
        ]);
    }
}
