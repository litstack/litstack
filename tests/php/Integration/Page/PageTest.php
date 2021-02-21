<?php

namespace Tests\Integration\Page;

use Ignite\Page\Page;
use Tests\BackendTestCase;
use Tests\Traits\ActingAsLitUserMock;

class PageTest extends BackendTestCase
{
    use ActingAsLitUserMock;

    /** @test */
    public function test_action()
    {
        $this->ActingAsLitUserMock();
        $page = new Page;
        $page->action('foo', DummyAction::class);
        $action = $page->getComponents()[0];
        $this->json('POST', '/admin/handle-event', array_merge(
            ['handler' => $action->getEvents()['run']],
            $action->getProp('eventData')
        ))
        ->assertStatus(200)
        ->assertJson(['message' => 'foo', 'variant' => 'success']);
    }
}

class DummyAction
{
    public function run()
    {
        return response()->success('foo');
    }
}
