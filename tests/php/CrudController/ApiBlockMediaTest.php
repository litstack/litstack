<?php

namespace FjordTest\CrudController;

use Fjord\Crud\Models\Media;
use FjordTest\BackendTestCase;
use FjordTest\FrontendTestCase;
use Fjord\Crud\Models\FormBlock;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use FjordTest\TestSupport\Models\Post;
use FjordTest\Traits\InteractsWithCrud;
use Illuminate\Support\Facades\DB;

/**
 * This test is using the Crud Post.
 * 
 * @see FjordApp\Config\Crud\PostConfig
 * @see FjordTest\TestSupport\Models\Post
 */
class ApiBlockMediaTest extends BackendTestCase
{
    use InteractsWithCrud;

    public function setUp(): void
    {
        parent::setUp();

        $this->post = Post::create([]);
        $this->actingAs($this->admin, 'fjord');
    }

    public function tearDown(): void
    {
        foreach (Media::all() as $media) {
            $media->delete();
        }
        File::cleanDirectory(storage_path('app/public'));

        parent::tearDown();
    }

    // Upload
    /** @test */
    public function it_can_upload_image()
    {
        return $this->markTestSkipped(
            'Media upload test is not working for validated request.'
        );
        $block = $this->createBlock();
        $this->assertNull($block->images);

        $url = $this->getCrudRoute("/{$this->post->id}/show/block/media_repeatables/{$block->id}/media");

        $response = $this->post($url, [
            'collection' => 'images',
            'media' => new UploadedFile(__DIR__ . "/../TestSupport/media/test_png.png", 'test_png.png'),
        ]);
        $response->assertStatus(200);

        $block = $this->getRepeatables()->first();
        $this->assertNotNull($block->images);
        $this->assertEquals('test_png.png', $block->images->first()->file_name);
    }

    // Update
    /** @test */
    public function it_can_update_media_properties()
    {
        return $this->markTestSkipped(
            'Media upload test is not working for validated request.'
        );
        $block = $this->createBlock();
        $this->assertNull($block->images);

        // Upload media.
        $url = $this->getCrudRoute("/{$this->post->id}/show/block/media_repeatables/{$block->id}/media");
        $response = $this->post($url, [
            'collection' => 'images',
            'media' => new UploadedFile(__DIR__ . "/../TestSupport/media/test_png.png", 'test_png.png'),
        ]);
        $response->assertStatus(200);

        // Update properties.
        $media = $this->getRepeatables()->first()->images->first();
        $url = $this->getCrudRoute("/{$this->post->id}/show/block/media_repeatables/{$block->id}/media/{$media->id}");
        $response = $this->put($url, [
            'custom_properties' => ['alt' => 'dummy alt', 'title' => 'dummy title'],
        ]);
        $response->assertStatus(200);
        $media = $this->getRepeatables()->first()->images->first();
        $this->assertArrayHasKey('alt', $media->custom_properties);
        $this->assertArrayHasKey('title', $media->custom_properties);
        $this->assertEquals('dummy alt', $media->custom_properties['alt']);
        $this->assertEquals('dummy title', $media->custom_properties['title']);
    }

    // Order
    /** @test */
    public function it_can_order_blocks_media()
    {
        $block = $this->createBlock();
        $this->assertNull($block->images);

        // Create 2 images.
        // TODO:
    }

    // Delete
    /** @test */
    public function it_can_delete_block_media()
    {
        // TODO:
    }

    public function createMedia($block, $orderColumn)
    {
        DB::table('media')->insert([
            'model_type' => FormBlock::class,
            'model_id' => $block->id,
            'collection_name' => 'media_repeatables',
            'uuid' => '',
            'name' => '',
            'file_name' => '',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 1,
            'manipulations' => '',
            'custom_properties' => '[]',
            'responsive_images' => '[]',
            'order_column' => $orderColumn,
            'created_at' => '',
            'updated_at' => '',
        ]);

        return DB::table('media')->orderByDesc('id')->first();
    }

    public function getRepeatables()
    {
        return FormBlock::where('model_type', get_class($this->post))
            ->where('model_id', $this->post->id)
            ->where('field_id', 'media_repeatables')
            ->where('type', 'image')
            ->get();
    }

    public function createBlock()
    {
        return FormBlock::create([
            'type' => 'image',
            'model_type' => get_class($this->post),
            'model_id' => $this->post->id,
            'field_id' => 'media_repeatables'
        ]);
    }
}
