<?php

namespace FjordTest\CrudController;

use FjordTest\BackendTestCase;
use FjordTest\TestSupport\Models\Post;
use FjordTest\Traits\InteractsWithCrud;

/**
 * This test is using the Crud Post.
 * 
 * @see FjordApp\Config\Crud\PostConfig
 * @see FjordTest\TestSupport\Models\Post
 */
class ApiUpdateTest extends BackendTestCase
{
    use InteractsWithCrud;

    public function setUp(): void
    {
        parent::setUp();

        $this->post = Post::create([]);
        $this->actingAs($this->admin, 'fjord');
    }

    public function refreshModel()
    {
        $this->post = $this->post->fresh();
    }

    /** @test */
    public function test_update()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/show");
        $response = $this->put($url);
        $response->assertStatus(200);
    }

    /** @test */
    public function test_update_returns_404_when_form_does_not_exist()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/other_form");
        $response = $this->put($url);
        $response->assertStatus(404);
    }

    /** @test */
    public function test_update_returns_404_when_model_does_not_exists()
    {
        $url = $this->getCrudRoute("/-1/other_form");
        $response = $this->put($url);
        $response->assertStatus(404);
    }

    /** @test */
    public function test_update_method_updates_attribute()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/show");

        $response = $this->put($url, ['title' => 'dummy title']);
        $response->assertStatus(200);

        $this->refreshModel();
        $this->assertEquals($this->post->title, 'dummy title');
    }
}
