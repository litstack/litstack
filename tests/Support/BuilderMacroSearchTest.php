<?php

namespace FjordTest;

use Closure;
use Mockery as m;
use BadMethodCallException;
use FjordTest\BackendTestCase;
use Fjord\Support\Macros\BuilderSearch;
use FjordTest\TestSupport\Models\Post;
use Illuminate\Database\Eloquent\Model;
use FjordTest\TestSupport\Models\User;
use Illuminate\Database\Eloquent\Builder;
use FjordTest\TestSupport\Models\TranslatablePost;

class BuilderMacroSearchTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->macro = new BuilderSearch();
        $this->migrate();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        m::close();
    }

    /** @test */
    public function it_returns_query_builder()
    {
        $this->assertInstanceOf(
            Builder::class,
            BuilderMacroSearchModel::search('name', 'rob')
        );
    }

    /** @test */
    public function it_notices_related_attributes()
    {
        $model = m::mock(BuilderMacroSearchModel::class);

        $this->passthruAllExcept($model, BuilderMacroSearchModel::class, ['posts']);
        $model->shouldReceive("search")->passthru();
        $model->shouldReceive('posts')->once()->passthru();

        // posts exists
        $model->search('posts.name', 'rob');

        // users doesnt exist, expect exception
        $this->expectException(BadMethodCallException::class);
        $model->search('users.name', 'rob');
    }

    public function test_whereAttributeLike_function()
    {
        $builder = m::mock(Builder::class);
        $builder->shouldReceive('getModel')->andReturn(new Post)->twice();

        $builder->shouldReceive('where')->with('text', 'LIKE', '%dan%')->once();
        $this->macro->whereAttributeLike($builder, 'text', 'dan', $or = false);

        $builder->shouldReceive('orWhere')->with('text', 'LIKE', '%dan%')->once();
        $this->macro->whereAttributeLike($builder, 'text', 'dan', $or = true);

        $builder->shouldReceive('getModel')->andReturn(new TranslatablePost)->twice();

        $builder->shouldReceive('whereTranslationLike')->with('text', '%dan%')->once();
        $this->macro->whereAttributeLike($builder, 'text', 'dan', $or = false);

        $builder->shouldReceive('orWhereTranslationLike')->with('text', '%dan%')->once();
        $this->macro->whereAttributeLike($builder, 'text', 'dan', $or = true);
    }

    /** @test */
    public function it_finds_using_attribute()
    {
        Post::firstOrCreate(['text' => 'hello']);
        $this->assertCount(1, Post::search('text', 'hello')->get());
        $this->assertCount(0, Post::search('text', 'other')->get());
    }

    /** @test */
    public function it_finds_using_multiple_attributes()
    {
        Post::firstOrCreate(['text' => 'first text', 'title' => 'first title']);
        Post::firstOrCreate(['text' => 'second text', 'title' => 'second title']);
        $this->assertCount(1, Post::search(['text', 'title'], 'first text')->get());
        $this->assertCount(1, Post::search(['text', 'title'], 'first title')->get());
        $this->assertCount(2, Post::search(['text', 'title'], 'title')->get());
        $this->assertCount(2, Post::search(['text', 'title'], 'text')->get());
        $this->assertCount(0, Post::search(['text', 'title'], 'other')->get());
    }

    /** @test */
    public function it_finds_using_relation_attribute()
    {
        $user = User::firstOrCreate(['name' => 'dan']);
        Post::firstOrCreate(['text' => 'bye', 'user_id' => $user->id]);

        $this->assertCount(1, Post::search('user.name', 'dan')->get());
        $this->assertCount(0, Post::search('user.name', 'rob')->get());
    }

    /** @test */
    public function it_finds_using_translated_attribute()
    {
        $post = TranslatablePost::firstOrCreate([]);
        $post->update([
            'en' => ['text' => 'english post'],
            'de' => ['text' => 'german post']
        ]);

        // Try one locale
        $posts = TranslatablePost::search('text', 'english')->get();
        $this->assertCount(1, $posts);
        // Try another locale
        $posts = TranslatablePost::search('text', 'german')->get();
        $this->assertCount(1, $posts);
        // Try not existing search term
        $posts = TranslatablePost::search('text', 'other')->get();
        $this->assertCount(0, $posts);
    }

    protected function applyWhereWithClosureToBuilderMock($builder)
    {
        return $builder->shouldReceive('where')->once()
            ->with(m::on(function ($closure) use ($builder) {
                $closure($builder);
                return $closure instanceof Closure;
            }));
    }
}

class RelationModel extends Model
{
}

class BuilderMacroSearchModel extends Model
{
    public function posts()
    {
        return $this->hasMany(RelationModel::class);
    }
}
