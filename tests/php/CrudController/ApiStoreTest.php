<?php

namespace FjordTest\CrudController;

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
class ApiStoreTest extends BackendTestCase
{
    use InteractsWithCrud;

    public function setUp(): void
    {
        parent::setUp();

        //$this->post = Post::create([]);
        $this->actingAs($this->admin, 'fjord');
    }

    /** @test */
    public function test_creationRule_validation_fails()
    {
        // Asserting 302 since we have the creationRule required for the title.
        $url = $this->getCrudRoute("/");
        $response = $this->post($url);
        $response->assertStatus(302);
    }

    /** @test */
    public function test_global_rule_validation_fails()
    {
        // Asserting 302 since we have the rule min:2 for the title.
        $url = $this->getCrudRoute("/");
        $response = $this->post($url, ['title' => 'a']);
        $response->assertStatus(302);
    }

    /** @test */
    public function it_stores_model()
    {
        $url = $this->getCrudRoute("/");
        $response = $this->post($url, ['title' => 'dummy title']);
        $response->assertStatus(200);

        $this->assertCount(1, Post::all());
        $this->assertEquals('dummy title', Post::first()->title);
    }
}
