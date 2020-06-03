<?php

namespace FjordTest\CrudController;

use FjordTest\BackendTestCase;
use FjordTest\FrontendTestCase;
use FjordTest\TestSupport\Models\Post;
use FjordTest\Traits\InteractsWithCrud;

class CrudControllerBlockTest extends BackendTestCase
{
    use InteractsWithCrud;

    public function setUp(): void
    {
        parent::setUp();

        //$this->post = Post::create([]);
        $this->actingAs($this->admin, 'fjord');
    }
    // TODO:
}
