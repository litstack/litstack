<?php

namespace Tests\Crud;

use Spatie\MediaLibrary\Conversions\Conversion;
use Tests\BackendTestCase;
use Tests\TestSupport\Models\Post;

class HasMediaTest extends BackendTestCase
{
    /** @test */
    public function it_registers_media_conversions_using_custom_conversions_key()
    {
        config()->set('lit.mediaconversions', [
            'test' => ['sm' => [300, 300, 8]],
        ]);

        $post = $this->partialMock(Post::class, function ($post) {
            $post->shouldReceive('addMediaConversion')
                ->twice()
                ->withArgs(fn ($key) => in_array($key, ['preview', 'sm']))
                ->andReturn(Conversion::create('test'));
        });

        $post->setConversionsKey('test');

        $post->registerMediaConversions();
    }
}
