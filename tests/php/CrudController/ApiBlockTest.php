<?php

namespace Tests\CrudController;

use Ignite\Crud\Models\Repeatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BackendTestCase;
use Tests\TestSupport\Models\Post;
use Tests\Traits\InteractsWithCrud;

/**
 * This test is using the Crud Post.
 *
 * @see Lit\Config\Crud\PostConfig
 * @see Tests\TestSupport\Models\Post
 */
class ApiBlockTest extends BackendTestCase
{
    use InteractsWithCrud;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake();
        $this->post = Post::create([]);
        $this->actingAs($this->admin, 'lit');
    }

    // Store

    /** @test */
    public function it_can_store_block()
    {
        $this->assertCount(0, $this->getRepeatables('text'));

        $url = $this->getCrudRoute("/{$this->post->id}/api/show/block/store");
        $request = $this->json('POST', $url, [
            'payload' => [
                'repeatable_type' => 'text',
            ],
            'field_id' => 'content',
        ]);

        $request->assertStatus(200);
        $this->assertCount(1, $this->getRepeatables('text'));
        $this->assertCount(1, $this->getRepeatables());

        $url = $this->getCrudRoute("/{$this->post->id}/api/show/block/store");
        $request = $this->post($url, [
            'payload' => [
                'repeatable_type' => 'input',
            ],
            'field_id' => 'content',
        ]);
        $request->assertStatus(200);

        // Asserting 2 repeatables at all.
        $this->assertCount(2, $this->getRepeatables());
        // And one with type input.
        $this->assertCount(1, $this->getRepeatables('input'));
    }

    // Load all

    /** @test */
    public function it_can_load_all_repeatables()
    {
        // Creating 2 repeatables.
        $this->createRepeatable();
        $this->createRepeatable();
        $this->assertCount(2, $this->getRepeatables());

        // Send request.
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/block/index");
        $request = $this->json('POST', $url, ['field_id' => 'content']);

        // Assertions.
        $request->assertStatus(200);
        $request->assertJsonCount(2);
    }

    // Load one

    /** @test */
    public function it_can_load_block()
    {
        // Creating block.
        $block = $this->createRepeatable('text');
        $this->assertCount(1, $this->getRepeatables('text'));

        // Send request.
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/block/load");
        $request = $this->json('POST', $url, [
            'field_id'      => 'content',
            'repeatable_id' => $block->id,
        ]);

        // Assertions.
        $request->assertStatus(200);
        $request->assertJson(['attributes' => $block->getAttributes()]);
    }

    // Delete

    /** @test */
    public function it_can_delete_block()
    {
        // Creating 2 block.
        $block1 = $this->createRepeatable('text');
        $block2 = $this->createRepeatable('text');
        $this->assertCount(2, $this->getRepeatables('text'));

        // Delete first block.
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/block/destroy");
        $request = $this->json('POST', $url, [
            'repeatable_id' => $block1->id,
            'field_id'      => 'content',
        ]);
        $request->assertStatus(200);
        $this->assertCount(1, $this->getRepeatables('text'));

        // Delete second block.
        $request = $this->json('POST', $url, [
            'repeatable_id' => $block2->id,
            'field_id'      => 'content',
        ]);
        $request->assertStatus(200);
        $this->assertCount(0, $this->getRepeatables('text'));
    }

    // Update

    /** @test */
    public function it_can_update_block()
    {
        // Creating 2 block.
        $block = $this->createRepeatable('text');
        $this->assertCount(1, $this->getRepeatables('text'));

        // Update block.
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/block");
        $request = $this->put($url, [
            'payload' => [
                app()->getLocale() => ['text' => 'some text'],
            ],
            'field_id'        => 'content',
            'repeatable_id'   => $block->id,
            'repeatable_type' => 'text',
        ]);
        $request->assertStatus(200);
        $block = $this->getRepeatables('text')->first();
        $this->assertEquals('some text', $block->text);
    }

    // /** @test */
    // public function it_can_upload_media_to_block()
    // {
    //     // Creating 2 block.
    //     $block = $this->createRepeatable('image', 'media_repeatables');
    //     $this->assertCount(1, $this->getRepeatables('image', 'media_repeatables'));

    //     // Update block.
    //     $url = $this->getCrudRoute("/{$this->post->id}/api/show/block");
    //     $request = $this->put($url, [
    //         'collection'      => 'images',
    //         'field_id'        => 'media_repeatables',
    //         'repeatable_id'   => $block->id,
    //         'repeatable_type' => 'image',
    //         'media'           => UploadedFile::fake()->image('test_png.png'),
    //     ]);
    //     $request->assertStatus(200);
    //     $block = $this->getRepeatables('image', 'media_repeatables')->first();
    //     $this->assertNotNull($block->images);
    //     $this->assertEquals('test_png.png', $block->images->first()->file_name);
    // }

    // Update

    /** @test */
    public function test_update_validates_request()
    {
        // Creating 2 block.
        $block = $this->createRepeatable('text');
        $this->assertCount(1, $this->getRepeatables('text'));

        // Text uses the rule "min:5"
        $url = $this->getCrudRoute("/{$this->post->id}/show/block/content/{$block->id}");
        $request = $this->put($url, [
            'text' => 'ab',
        ]);
        //$request->assertStatus(302);
        $block = $this->getRepeatables('text')->first();
        $this->assertEquals('', $block->text);
    }

    public function getRepeatables($type = null, $fieldId = 'content')
    {
        $query = Repeatable::where('model_type', get_class($this->post))
            ->where('model_id', $this->post->id)
            ->where('field_id', $fieldId);

        if ($type) {
            $query->where('type', $type);
        }

        return $query->get();
    }

    public function createRepeatable($type = 'text', $fieldId = 'content')
    {
        return Repeatable::create([
            'config_type' => \Lit\Config\Crud\PostConfig::class,
            'type'        => $type,
            'model_type'  => get_class($this->post),
            'model_id'    => $this->post->id,
            'field_id'    => $fieldId,
        ]);
    }
}
