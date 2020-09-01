<?php

namespace Lit\Test\Page;

use Ignite\Page\Actions\ActionModal;
use Ignite\Vue\Traits\StaticComponentName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestHelpers;

class ActionModalTest extends TestCase
{
    use TestHelpers;

    /** @test */
    public function it_is_static_bootstrap_modal_component()
    {
        $modal = new ActionModal();
        $this->assertHasTrait(StaticComponentName::class, ActionModal::class);
        $this->assertSame('b-modal', $modal->getName());
    }

    /** @test */
    public function test_message_method()
    {
        $modal = new ActionModal();
        $this->assertInstanceOf(ActionModal::class, $modal->message('hello'));
        $this->assertSame('hello', $modal->getProp('message'));
    }

    /** @test */
    public function test_title_method()
    {
        $modal = new ActionModal();
        $this->assertInstanceOf(ActionModal::class, $modal->title('wooow'));
        $this->assertSame('wooow', $modal->getProp('title-html'));
    }

    /** @test */
    public function test_size_method()
    {
        $modal = new ActionModal();
        $this->assertInstanceOf(ActionModal::class, $modal->size('xl'));
        $this->assertSame('xl', $modal->getProp('size'));
    }

    /** @test */
    public function test_size_method_fails_for_invlaid_size()
    {
        $this->expectException(InvalidArgumentException::class);
        $modal = new ActionModal();
        $modal->size('invalid');
    }

    /** @test */
    public function test_confirmVariant_method()
    {
        $modal = new ActionModal();
        $this->assertInstanceOf(ActionModal::class, $modal->confirmVariant('secondary'));
        $this->assertSame('secondary', $modal->getProp('ok-variant'));
    }

    /** @test */
    public function test_confirmText_method()
    {
        $modal = new ActionModal();
        $this->assertInstanceOf(ActionModal::class, $modal->confirmText('hello'));
        $this->assertSame('hello', $modal->getProp('ok-title-html'));
    }

    /** @test */
    public function test_confirmOnly_method()
    {
        $modal = new ActionModal();
        $this->assertInstanceOf(ActionModal::class, $modal->confirmOnly());
        $this->assertSame(true, $modal->getProp('ok-only'));

        $modal->confirmOnly(false);
        $this->assertSame(false, $modal->getProp('ok-only'));
    }

    /** @test */
    public function test_cancelVariant_method()
    {
        $modal = new ActionModal();
        $this->assertInstanceOf(ActionModal::class, $modal->cancelVariant('info'));
        $this->assertSame('info', $modal->getProp('cancel-variant'));
    }

    /** @test */
    public function test_cancelText_method()
    {
        $modal = new ActionModal();
        $this->assertInstanceOf(ActionModal::class, $modal->cancelText('nope'));
        $this->assertSame('nope', $modal->getProp('cancel-title-html'));
    }

    /** @test */
    public function test_render_method()
    {
        $rendered = (new ActionModal())->render();
        $this->assertArrayHasKey('form', $rendered);
    }
}
