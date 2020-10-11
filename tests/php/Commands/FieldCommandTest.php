<?php

namespace Tests\Commands;

use Tests\BackendTestCase;

class FieldCommandTest extends BackendTestCase
{
    /** @test */
    public function it_generates_files()
    {
        $this->artisan('lit:field Foo');

        // Field Class
        $this->assertFileExists(base_path('lit/app/Fields/Foo.php'));
        $this->assertTrue(is_subclass_of(\Lit\Fields\Foo::class, \Ignite\Crud\BaseField::class));
        // Component
        $this->assertFileExists(base_path('lit/resources/js/components/Fields/Foo.vue'));
    }
}
