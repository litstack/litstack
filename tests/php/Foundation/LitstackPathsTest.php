<?php

namespace Tests\Foundation;

use Tests\BackendTestCase;

class LitstackPathsTest extends BackendTestCase
{
    /** @test */
    public function test_basePath_method()
    {
        $litstack = app(Litstack::class);
        $this->assertSame('abc', $litstack->basePath());
    }

    // TODO: TESTS
    /**
     * - resourcePath
     * - vendorPath
     * - langPath
     * - path
     * - basePath.
     */
}
