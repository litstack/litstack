<?php

namespace FjordTest\Foundation;

use FjordTest\BackendTestCase;
use FjordTest\Traits\ActingAsFjordUserMock;
use Throwable;

/**
 * Testing view [fjord::app].
 */
class FjordAppViewTest extends BackendTestCase
{
    use ActingAsFjordUserMock;

    /** @test */
    public function it_fails_when_component_data_is_missing()
    {
        $this->expectException(Throwable::class);
        view('fjord::app')->render();
    }

    // TODO: Test it sets view data.
    // /** @test */
    // public function test_view_gets_data()
    // {
    //     $view = view('fjord::app')->withComponent('dummy');

    //     $view->render();

    //     dd($view->getData);
    // }
}
