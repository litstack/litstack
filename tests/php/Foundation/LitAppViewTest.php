<?php

namespace Tests\Foundation;

use Tests\BackendTestCase;
use Tests\Traits\ActingAsLitUserMock;
use Throwable;

/**
 * Testing view [lit::app].
 */
class LitAppViewTest extends BackendTestCase
{
    use ActingAsLitUserMock;

    /** @test */
    public function it_fails_when_component_data_is_missing()
    {
        $this->expectException(Throwable::class);
        view('litstack::app')->render();
    }

    // TODO: Test it sets view data.
    // /** @test */
    // public function test_view_gets_data()
    // {
    //     $view = view('lit::app')->withComponent('dummy');

    //     $view->render();

    //     dd($view->getData);
    // }
}
