<?php

namespace Tests\Browser;

use Lit\Crud\Models\FormBlock;
use LitApp\Config\Crud\BlockInBlockConfig;
use Tests\FrontendTestCase;
use Tests\TestSupport\Models\Post;
use Tests\Traits\InteractsWithCrud;

class BlockInBlockTest extends FrontendTestCase
{
    use InteractsWithCrud;

    protected $config = BlockInBlockConfig::class;

    public function setUp(): void
    {
        parent::setUp();

        $this->post = Post::create([]);
        $this->actingAs($this->admin, 'lit');
    }

    /** @test */
    public function test_parent_block_can_be_created()
    {
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $url = $this->getCrudRoute("/{$this->post->id}/");
            $browser
                ->loginAs($this->admin, 'lit')
                ->visit($url)
                ->waitFor('.lit-block-content')
                ->click('.lit-block-content .lit-block-add-card')
                ->waitFor('.lit-block-content .lit-block')
                ->waitUsing(1, 10, function () {
                    return ! $this->post->refresh()->content->isEmpty();
                }, 'Parent Block was not created.');
        });
    }

    /** @test */
    public function test_child_block_can_be_created()
    {
        // $this->skipIfChromedriverIsNotRunning();
        $this->markTestSkipped('');

        $this->browse(function ($browser) {
            $url = $this->getCrudRoute("/{$this->post->id}/");
            $browser
                 ->loginAs($this->admin, 'lit')
                 ->visit($url)
                 ->waitFor('.lit-block-content')
                 ->click('.lit-block-content .lit-block-add-card')
                 ->waitFor('.lit-block-content .lit-block')
                 ->click('.lit-block-content .lit-block-card .lit-block-add-text')
                 ->waitFor('.lit-block-card .lit-block', 10)
                 ->waitUsing(1, 10, function () {
                     return ! $this->post->refresh()->content->first()->card->isEmpty();
                 }, 'Child block was not created.')
                 ->waitUsing(1, 10, function () {
                     return $this->post->content->first()->card->first() instanceof FormBlock;
                 }, 'Child block is not an instanceof FormBlock.');
        });
    }

    /** @test */
    public function test_child_block_data_can_be_updated()
    {
        // $this->skipIfChromedriverIsNotRunning();
        $this->markTestSkipped('');

        $this->browse(function ($browser) {
            $url = $this->getCrudRoute("/{$this->post->id}/");
            $browser
                 ->loginAs($this->admin, 'lit')
                 ->visit($url)
                 ->waitFor('.lit-block-content')
                 ->click('.lit-block-content .lit-block-add-card')
                 ->waitFor('.lit-block-content .lit-block')
                 ->click('.lit-block-content .lit-block-card .lit-block-add-text')
                 ->waitFor('.lit-block-card .lit-block', 10)
                 ->type('.lit-block-card .lit-block textarea', 'Hello World')
                 ->waitFor('.lit-save-button .btn-primary')
                 ->click('.lit-save-button .btn-primary')
                ->waitUsing(1, 10, function () {
                    $repeatable = $this->post->refresh()->content->first()->card->first();

                    return $repeatable->text == 'Hello World';
                }, "Child block data wasn't updated.");
        });
    }
}
