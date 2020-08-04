<?php

namespace FjordTest\Browser;

use Fjord\Crud\Models\FormBlock;
use FjordApp\Config\Crud\BlockInBlockConfig;
use FjordTest\FrontendTestCase;
use FjordTest\TestSupport\Models\Post;
use FjordTest\Traits\InteractsWithCrud;

class BlockInBlockTest extends FrontendTestCase
{
    use InteractsWithCrud;

    protected $config = BlockInBlockConfig::class;

    public function setUp(): void
    {
        parent::setUp();

        $this->post = Post::create([]);
        $this->actingAs($this->admin, 'fjord');
    }

    /** @test */
    public function test_parent_block_can_be_created()
    {
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $url = $this->getCrudRoute("/{$this->post->id}/");
            $browser
                ->loginAs($this->admin, 'fjord')
                ->visit($url)
                ->waitFor('.fj-block-content')
                ->click('.fj-block-content .fj-block-add-card')
                ->waitFor('.fj-block-content .fj-block')
                ->waitUsing(1, 10, function () {
                    return ! $this->post->refresh()->content->isEmpty();
                }, 'Parent Block was not created.');
        });
    }

    /** @test */
    public function test_child_block_can_be_created()
    {
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $url = $this->getCrudRoute("/{$this->post->id}/");
            $browser
                 ->loginAs($this->admin, 'fjord')
                 ->visit($url)
                 ->waitFor('.fj-block-content')
                 ->click('.fj-block-content .fj-block-add-card')
                 ->waitFor('.fj-block-content .fj-block')
                 ->click('.fj-block-content .fj-block-card .fj-block-add-text')
                 ->waitFor('.fj-block-card .fj-block', 10)
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
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $url = $this->getCrudRoute("/{$this->post->id}/");
            $browser
                 ->loginAs($this->admin, 'fjord')
                 ->visit($url)
                 ->waitFor('.fj-block-content')
                 ->click('.fj-block-content .fj-block-add-card')
                 ->waitFor('.fj-block-content .fj-block')
                 ->click('.fj-block-content .fj-block-card .fj-block-add-text')
                 ->waitFor('.fj-block-card .fj-block', 10)
                 ->type('.fj-block-card .fj-block textarea', 'Hello World')
                 ->waitFor('.fj-save-button .btn-primary')
                 ->click('.fj-save-button .btn-primary')
                ->waitUsing(1, 10, function () {
                    $repeatable = $this->post->refresh()->content->first()->card->first();

                    return $repeatable->text == 'Hello World';
                }, "Child block data wasn't updated.");
        });
    }
}
