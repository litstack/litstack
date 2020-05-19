<?php

namespace Fjord\Test;

use Closure;
use Mockery as m;
use BadMethodCallException;
use Fjord\Test\FjordTestCase;
use Fjord\Support\Macros\BuilderSearch;
use Fjord\Test\TestSupport\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Fjord\Test\TestSupport\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Fjord\Test\TestSupport\Models\TranslatablePost;

class BuilderMacroSearchTest extends FjordTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->macro = new BuilderSearch();
        $this->migrateSupportTables();
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
        $author = Author::firstOrCreate(['name' => 'dan']);
        Post::firstOrCreate(['text' => 'bye', 'author_id' => $author->id]);

        $this->assertCount(1, Post::search('author.name', 'dan')->get());
        $this->assertCount(0, Post::search('author.name', 'rob')->get());
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

    protected function passthruAllExcept($mock, $class, array $without)
    {
        $methods = get_class_methods($class);
        foreach ($without as $method) {
            unset($methods[array_search($method, $methods)]);
        }
        $mock->shouldReceive(...$methods)->passthru();
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
