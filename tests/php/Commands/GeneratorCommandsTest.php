<?php

namespace Tests\Commands;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\CastMakeCommand;
use Illuminate\Support\Facades\File;
use Tests\BackendTestCase;

class GeneratorCommandsTest extends BackendTestCase
{
    public function tearDown(): void
    {
        File::deleteDirectory(base_path('lit/app/Http/Livewire'));
        File::deleteDirectory(base_path('lit/resources/views/livewire'));
        File::deleteDirectory(base_path('lit/app/Jobs'));
        File::deleteDirectory(base_path('lit/app/View'));
        File::deleteDirectory(base_path('lit/app/Casts'));

        parent::tearDown();
    }

    /** @test */
    public function it_creates_livewire_component_and_view()
    {
        $this->artisan('lit:livewire', ['name' => 'foo']);
        $this->assertFileExists(base_path('lit/app/Http/Livewire/Foo.php'));
        $this->assertFileExists(base_path('lit/resources/views/livewire/foo.blade.php'));
        $this->assertTrue(class_exists(\Lit\Http\Livewire\Foo::class));
    }

    /** @test */
    public function it_passes_inline_to_livewire_component()
    {
        $this->artisan('lit:livewire', ['name' => 'foo', '--inline' => true]);
        $this->assertFileExists(base_path('lit/app/Http/Livewire/Foo.php'));
        $this->assertFalse(file_exists(base_path('lit/resources/views/livewire/foo.blade.php')));
    }

    /** @test */
    public function it_fixes_livewire_component_view_namespace()
    {
        $this->artisan('lit:livewire', ['name' => 'foo']);
        $this->assertFileExists(base_path('lit/app/Http/Livewire/Foo.php'));
        $this->assertStringContainsString('lit::livewire.foo', File::get(base_path('lit/app/Http/Livewire/Foo.php')));
    }

    /** @test */
    public function it_creates_job()
    {
        $this->artisan('lit:job', ['name' => 'foo']);
        $this->assertFileExists(lit()->getPath('app/Jobs/Foo.php'));
    }

    /** @test */
    public function it_creates_cast()
    {
        if (! class_exists(CastMakeCommand::class)) {
            $this->markTestSkipped('Cast command not available in Laravel '.Application::VERSION);
        }
        $this->artisan('lit:cast', ['name' => 'foo']);
        $this->assertFileExists(lit()->getPath('app/Casts/Foo.php'));
    }

    /** @test */
    public function it_creates_request()
    {
        $this->artisan('lit:request', ['name' => 'foo']);
        $this->assertFileExists(lit()->getPath('app/Http/Requests/Foo.php'));
    }

    /** @test */
    public function it_creates_provider()
    {
        $this->artisan('lit:provider', ['name' => 'foo']);
        $this->assertFileExists(lit()->getPath('app/Providers/Foo.php'));
    }

    /** @test */
    public function it_creates_resource()
    {
        $this->artisan('lit:resource', ['name' => 'foo']);
        $this->assertFileExists(lit()->getPath('app/Http/Resources/Foo.php'));
    }

    /** @test */
    public function it_creates_middleware()
    {
        $this->artisan('lit:middleware', ['name' => 'foo']);
        $this->assertFileExists(lit()->getPath('app/Http/Middleware/Foo.php'));
    }

    /** @test */
    public function it_create_component_and_its_view()
    {
        $this->artisan('lit:component', ['name' => 'Foo']);
        $this->assertFileExists(lit()->getPath('app/View/Components/Foo.php'));
        $this->assertFileExists(lit()->getPath('resources/views/components/foo.blade.php'));
    }

    /** @test */
    public function it_fixes_component_view_namespace()
    {
        $this->artisan('lit:component', ['name' => 'Foo']);
        $view = (new \Lit\View\Components\Foo())->render();
        $this->assertSame('lit::components.foo', $view->getName());
    }
}
