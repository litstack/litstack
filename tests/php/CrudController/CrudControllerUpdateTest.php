<?php

namespace FjordTest\CrudController;

use FjordTest\BackendTestCase;
use FjordTest\TestSupport\Models\Post;
use FjordTest\Traits\InteractsWithCrud;


class CrudControllerUpdateTest extends BackendTestCase
{
    use InteractsWithCrud;

    public function setUp(): void
    {
        parent::setUp();

        $this->post = Post::create([]);
    }

    /** @test */
    public function test_update()
    {
        $response = $this->put($this->getCrudRoute("/{$this->post->id}/form"));
        $response->assertStatus(200);
    }
}
