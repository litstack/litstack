<?php

namespace Tests\Commands;

use Tests\BackendTestCase;

class FormMacroCommandTest extends BackendTestCase
{
    /** @test */
    public function it_creates_form_macro()
    {
        $this->artisan('lit:form-macro Foo');

        $this->assertFileExists(base_path('lit/app/Macros/Form/FooMacro.php'));
    }
}
