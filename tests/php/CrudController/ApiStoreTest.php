<?php

namespace Tests\CrudController;

use Tests\BackendTestCase;
use Tests\TestSupport\Models\Post;
use Tests\Traits\InteractsWithCrud;

/**
 * This test is using the Crud Post.
 *
 * @see LitApp\Config\Crud\PostConfig
 * @see Tests\TestSupport\Models\Post
 */
class ApiStoreTest extends BackendTestCase
{
    use InteractsWithCrud;

    public function setUp(): void
    {
        parent::setUp();

        //$this->post = Post::create([]);
        $this->actingAs($this->admin, 'lit');
    }

    /** @test */
    public function test_creationRule_validation_fails()
    {
        // Asserting 302 since we have the creationRule required for the title.
        $url = $this->getCrudRoute('/api/show');
        $response = $this->post($url);
        $response->assertStatus(302);
    }

    /** @test */
    public function test_global_rule_validation_fails()
    {
        // Asserting 302 since we have the rule min:2 for the title.
        $url = $this->getCrudRoute('/api/show');
        $response = $this->post($url, ['payload' => ['title' => 'a']]);
        $response->assertStatus(302);
    }

    /** @test */
    public function it_stores_model()
    {
        $url = $this->getCrudRoute('/api/show');
        $response = $this->post($url, [
            'payload' => ['title' => 'dummy title'],
        ]);
        $response->assertStatus(200);

        $this->assertCount(1, Post::all());
        $this->assertEquals('dummy title', Post::first()->title);
    }
}
