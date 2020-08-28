<?php

namespace Tests;

use Ignite\Support\LoadsLivewireComponents;

class LoadsLivewireComponentsTest extends BackendTestCase
{
    /** @test */
    public function test_guessClassName_method()
    {
        $provider = new LoadsLivewireComponentsServiceProvider;

        $result = $this->callUnaccessibleMethod($provider, 'guessClassName', [
            'foo/bar/Counter.php', 'Livewire', 'foo/bar',
        ]);
        $this->assertEquals('Livewire\\Counter', $result);

        $result = $this->callUnaccessibleMethod($provider, 'guessClassName', [
            'foo/bar/Baz/Counter.php', 'Livewire', 'foo/bar',
        ]);
        $this->assertEquals('Livewire\\Baz\\Counter', $result);
    }

    /** @test */
    public function test_getComponentName()
    {
        $provider = new LoadsLivewireComponentsServiceProvider;

        $result = $this->callUnaccessibleMethod($provider, 'getComponentName', [
            'Livewire\\Counter', 'Livewire', 'foo',
        ]);
        $this->assertEquals('foo::counter', $result);

        $result = $this->callUnaccessibleMethod($provider, 'getComponentName', [
            'Livewire\\Bar\\Counter', 'Livewire', 'foo',
        ]);
        $this->assertEquals('foo::bar.counter', $result);

        $result = $this->callUnaccessibleMethod($provider, 'getComponentName', [
            'Livewire\\Bar\\BazCounter', 'Livewire', 'foo',
        ]);
        $this->assertEquals('foo::bar.baz_counter', $result);
    }
}

class LoadsLivewireComponentsServiceProvider
{
    use LoadsLivewireComponents;
}
