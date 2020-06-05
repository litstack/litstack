<?php

namespace FjordTest\CrudController;

use FjordTest\BackendTestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use FjordTest\TestSupport\Models\Post;
use FjordTest\Traits\InteractsWithCrud;
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
    public function it_returns_404_when_form_does_not_exist()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/other_form/media");
        $response = $this->post($url);
        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_model_does_not_exist()
    {
        $url = $this->getCrudRoute("/-1/form/media");
        $response = $this->post($url);
        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_no_collection_is_given()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/show/media");
        $response = $this->post($url);
        $response->assertStatus(404);
    }

    /** @test */

    public function it_returns_404_when_field_does_not_exists()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/show/media");
        $response = $this->post($url, [
            'collection' => 'other_field'
        ]);
        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_media_is_not_sent()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/show/media");
        $response = $this->post($url, [
            'collection' => 'test_image'
        ]);
        $response->assertStatus(404);
    }

    /** @test */
    public function it_uploads_single_image()
    {
        $this->assertNull($this->post->test_image);

        $url = $this->getCrudRoute("/{$this->post->id}/show/media");
        $response = $this->post($url, [
            'collection' => 'test_image',
            'media' => new UploadedFile(__DIR__ . "/../TestSupport/media/test_png.png", 'test_png.png'),
        ]);
        $response->assertStatus(200);

        $this->refreshModel();
        $this->assertNotNull($this->post->test_image);
        $this->assertEquals('test_png.png', $this->post->test_image->file_name);
    }

    /** @test */
    public function it_can_destroy_image()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/show/media");
        $response = $this->post($url, [
            'collection' => 'test_image',
            'media' => new UploadedFile(__DIR__ . "/../TestSupport/media/test_png.png", 'test_png.png'),
        ]);
        $response->assertStatus(200);

        $this->refreshModel();
        $this->assertNotNull($this->post->test_image);

        $url = $this->getCrudRoute("/{$this->post->id}/show/media");
        $response = $this->post($url, [
            'collection' => 'test_image',
            'media' => new UploadedFile(__DIR__ . "/../TestSupport/media/test_png.png", 'test_png.png'),
        ]);
    }

    /** @test */
    public function it_cannot_upload_multiple_images_when_maxFiles_one()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/show/media");
        $response = $this->post($url, [
            'collection' => 'test_image',
            'media' => new UploadedFile(__DIR__ . "/../TestSupport/media/test_png.png", 'test_png.png'),
        ]);
        $response->assertStatus(200);

        $response = $this->post($url, [
            'collection' => 'test_image',
            'media' => new UploadedFile(__DIR__ . "/../TestSupport/media/test_png.png", 'test_png.png'),
        ]);
        $response->assertStatus(405);
    }

    /** @test */
    public function test_image_upload_for_maxFiles_greate_than_one()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/show/media");
        $response = $this->post($url, [
            'collection' => 'test_images',
            'media' => new UploadedFile(__DIR__ . "/../TestSupport/media/test_png.png", 'test_png.png'),
        ]);
        $response->assertStatus(200);

        $response = $this->post($url, [
            'collection' => 'test_images',
            'media' => new UploadedFile(__DIR__ . "/../TestSupport/media/test_png.png", 'test_png.png'),
        ]);
        $response->assertStatus(200);

        $response = $this->post($url, [
            'collection' => 'test_images',
            'media' => new UploadedFile(__DIR__ . "/../TestSupport/media/test_png.png", 'test_png.png'),
        ]);
        $response->assertStatus(405);
    }
}
