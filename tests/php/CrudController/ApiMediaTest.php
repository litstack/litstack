<?php

namespace FjordTest\CrudController;

use FjordTest\BackendTestCase;
use FjordTest\TestSupport\Models\Post;
use FjordTest\Traits\InteractsWithCrud;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * This test is using the Crud Post.
 *
 * @see FjordApp\Config\Crud\PostConfig
 * @see FjordTest\TestSupport\Models\Post
 */
class ApiMediaTest extends BackendTestCase
{
    use InteractsWithCrud;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake();
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

    public function refreshModel()
    {
        $this->post = $this->post->fresh();
    }

    /** @test */
    public function it_returns_404_when_no_collection_is_given()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/media/store");
        $response = $this->post($url);
        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_media_is_not_sent()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/media");
        $response = $this->post($url, [
            'collection' => 'test_image',
        ]);
        $response->assertStatus(404);
    }

    /** @test */
    public function it_uploads_single_image()
    {
        $this->assertNull($this->post->test_image);

        $url = $this->getCrudRoute("/{$this->post->id}/api/show/media");
        $response = $this->post($url, [
            'field_id'   => 'test_image',
            'collection' => 'test_image',
            'media'      => UploadedFile::fake()->image('test_png.png'),
        ]);
        $response->assertStatus(200);

        $this->refreshModel();
        $this->assertNotNull($this->post->test_image);
        $this->assertEquals('test_png.png', $this->post->test_image->file_name);
    }

    /** @test */
    public function it_cannot_upload_multiple_images_when_maxFiles_one()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/media");
        $response = $this->post($url, [
            'field_id'   => 'test_image',
            'collection' => 'test_image',
            'media'      => UploadedFile::fake()->image('test_png.png'),
        ]);
        $response->assertStatus(200);

        $response = $this->post($url, [
            'field_id'   => 'test_image',
            'collection' => 'test_image',
            'media'      => UploadedFile::fake()->image('test_png.png'),
        ]);
        $response->assertStatus(405);
    }

    /** @test */
    public function test_image_upload_for_maxFiles_greate_than_one()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/api/show/media");
        $response = $this->post($url, [
            'field_id'   => 'test_images',
            'collection' => 'test_images',
            'media'      => UploadedFile::fake()->image('test_png.png'),
        ]);
        $response->assertStatus(200);

        $response = $this->post($url, [
            'field_id'   => 'test_images',
            'collection' => 'test_images',
            'media'      => UploadedFile::fake()->image('test_png.png'),
        ]);
        $response->assertStatus(200);

        $response = $this->post($url, [
            'field_id'   => 'test_images',
            'collection' => 'test_images',
            'media'      => UploadedFile::fake()->image('test_png.png'),
        ]);
        $response->assertStatus(405);
    }
}
