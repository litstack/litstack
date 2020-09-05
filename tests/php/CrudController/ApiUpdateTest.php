<?php

namespace Tests\CrudController;

use Tests\FrontendTestCase;
use Tests\TestSupport\Models\Post;
use Tests\TestSupport\Models\User;
use Tests\Traits\InteractsWithCrud;

/**
 * This test is using the Crud Post.
 *
 * @see Lit\Config\Crud\PostConfig
 * @see Tests\TestSupport\Models\Post
 */
class ApiUpdateTest extends FrontendTestCase
{
    use InteractsWithCrud;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::create(['name' => 'foo']);
        $this->post = Post::create(['user_id' => $this->user->id]);
        $this->actingAs($this->admin, 'lit');
    }

    public function refreshModel()
    {
        $this->post = $this->post->fresh();
    }

    /** @test */
    public function test_update()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/api/show");
        $response = $this->put($url);
        $response->assertStatus(200);
    }

    /** @test */
    public function test_update_method_updates_attribute()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/api/show");

        $response = $this->put($url, ['payload' => ['title' => 'dummy title']]);
        $response->assertStatus(200);

        $this->refreshModel();
        $this->assertEquals($this->post->title, 'dummy title');
    }

    /** @test */
    public function test_update_related_attributes()
    {
        $url = $this->getCrudRoute("/{$this->post->id}/api/show");

        $response = $this->put($url, [
            'child_field_id' => 'name',
            'field_id'       => 'user',
            'payload'        => [
                'name' => 'bar',
            ],
            'relation_id' => $this->user->id,
        ]);
        $response->assertStatus(200);

        $this->assertEquals($this->user->refresh()->name, 'bar');
    }
}
