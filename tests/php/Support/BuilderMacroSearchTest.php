<?php

namespace Tests;

use BadMethodCallException;
use Ignite\Crud\Models\Relation;
use Ignite\Support\Macros\BuilderSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Mockery as m;
use Tests\TestSupport\Models\Post;
use Tests\TestSupport\Models\Tag;
use Tests\TestSupport\Models\TranslatablePost;
use Tests\TestSupport\Models\User;

class BuilderMacroSearchTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->macro = new BuilderSearch();
        $this->migrate();
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
        $model = m::mock(BuilderMacroSearchModel::class)->makePartial();

        $model->shouldReceive('search')->twice()->passthru();
        $model->shouldReceive('posts')->once()->passthru();

        // posts exists
        $model->search('posts.name', 'rob');

        // users doesnt exist, expect exception
        $this->expectException(BadMethodCallException::class);
        $model->search('users.name', 'rob');
    }

    public function test_whereAttributeLike_function_for_non_translatable_model()
    {
        $builder = m::mock(Builder::class);
        $builder->shouldReceive('getModel')->andReturn(new Post())->times(4);

        $builder->shouldReceive('where')->with('posts.text', 'LIKE', '%dan%')->once();
        $this->macro->whereAttributeLike($builder, 'text', 'dan', $or = false);

        $builder->shouldReceive('orWhere')->with('posts.text', 'LIKE', '%dan%')->once();
        $this->macro->whereAttributeLike($builder, 'text', 'dan', $or = true);
    }

    /** @test */
    public function test_whereAttributeLike_function_for_translatable_model()
    {
        $builder = m::mock(Builder::class);
        $builder->shouldReceive('getModel')->andReturn(new TranslatablePost());

        $builder->shouldReceive('whereTranslationLike')->with('text', '%dan%')->once();
        $this->macro->whereAttributeLike($builder, 'text', 'dan', $or = false);

        $builder->shouldReceive('orWhereTranslationLike')->with('text', '%dan%')->once();
        $this->macro->whereAttributeLike($builder, 'text', 'dan', $or = true);

        $builder->shouldReceive('where')->with('t_posts.other', 'LIKE', '%dan%')->once();
        $this->macro->whereAttributeLike($builder, 'other', 'dan', $or = false);

        $builder->shouldReceive('orWhere')->with('t_posts.other', 'LIKE', '%dan%')->once();
        $this->macro->whereAttributeLike($builder, 'other', 'dan', $or = true);
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
    public function it_finds_using_relation_attribute_when_one_model_exists()
    {
        $user = User::firstOrCreate(['name' => 'dan']);
        Post::firstOrCreate(['text' => 'bye', 'user_id' => $user->id]);

        $this->assertCount(1, Post::search('user.name', 'dan')->get());
        $this->assertCount(0, Post::search('user.name', 'rob')->get());
    }

    /** @test */
    public function it_finds_using_relation_attribute_when_multiple_models_exist()
    {
        $related = User::create(['name' => 'dan']);
        $model = Post::create(['text' => 'bye', 'user_id' => $related->id]);

        $related2 = User::create(['name' => 'other related']);
        $model2 = Post::create(['text' => 'other model', 'user_id' => $related2->id]);

        $this->assertCount(1, Post::search('user.name', 'dan')->get());
        $this->assertEquals($model->id, Post::search('user.name', 'dan')->first()->id);
        $this->assertCount(0, Post::search('user.name', 'something that doesnt exist')->get());
    }

    /** @test */
    public function it_finds_using_translated_attribute_when_one_model_exists()
    {
        $post = TranslatablePost::create([]);
        $post->update([
            'en' => ['text' => 'english post'],
            'de' => ['text' => 'german post'],
        ]);

        // Try one locale
        $posts = TranslatablePost::search('text', 'english')->get();
        $this->assertCount(1, $posts);
        // Try another locale
        $posts = TranslatablePost::search('text', 'german')->get();
        $this->assertCount(1, $posts);
        // Try not existing search term
        $posts = TranslatablePost::search('text', 'something that doesnt exist')->get();
        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_finds_using_translated_attribute_when_multiple_models_exist()
    {
        $model = TranslatablePost::create([]);
        $model->update([
            'en' => ['text' => 'english post'],
            'de' => ['text' => 'german post'],
        ]);
        $model2 = TranslatablePost::create([]);
        $model2->update([
            'en' => ['text' => 'other'],
            'de' => ['text' => 'other'],
        ]);

        // Try one locale
        $models = TranslatablePost::search('text', 'english')->get();
        $this->assertCount(1, $models);
        // Try another locale
        $models = TranslatablePost::search('text', 'german')->get();
        $this->assertCount(1, $models);
        // Try not existing search term
        $models = TranslatablePost::search('text', 'something that doesnt exist')->get();
        $this->assertCount(0, $models);
    }

    /** @test */
    public function it_finds_using_related_translated_attribute()
    {
        $model = TranslatablePost::create([]);
        $model->update([
            'en' => ['text' => 'english model'],
            'de' => ['text' => 'german model'],
        ]);
        $related = TranslatablePost::create([]);
        $related->update([
            'en' => ['text' => 'related english related'],
            'de' => ['text' => 'related german related'],
        ]);

        factory(Relation::class, 1)->create([
            'name' => 'translatablePosts',
            'from' => $model,
            'to'   => $related,
        ]);

        $results = TranslatablePost::search('translatablePosts.text', 'related english')->get();
        $this->assertCount(1, $results);

        $results = TranslatablePost::search('translatablePosts.text', 'model')->get();
        $this->assertCount(0, $results);
    }

    /** @test */
    public function it_finds_using_nested_relation_attributes()
    {
        $red = Tag::create(['title' => 'red']);
        $blue = Tag::create(['title' => 'blue']);
        $green = Tag::create(['title' => 'green']);
        $yellow = Tag::create(['title' => 'yellow']);

        // red, blue
        $related1 = User::create(['name' => '']);
        $this->addTag($related1, $red);
        $this->addTag($related1, $blue);
        $model1 = Post::create(['user_id' => $related1->id]);

        // red, green
        $related2 = User::create(['name' => '']);
        $this->addTag($related2, $red);
        $this->addTag($related2, $green);
        $model2 = Post::create(['user_id' => $related2->id]);

        // red, blue, yellow
        $related3 = User::create(['name' => '']);
        $this->addTag($related3, $red);
        $this->addTag($related3, $blue);
        $this->addTag($related3, $yellow);
        $model3 = Post::create(['user_id' => $related3->id]);

        $this->assertCount(3, Post::search('user.tags.title', 'red')->get());
        $this->assertCount(2, Post::search('user.tags.title', 'blue')->get());
        $this->assertCount(1, Post::search('user.tags.title', 'green')->get());
        $this->assertCount(1, Post::search('user.tags.title', 'yellow')->get());
        $this->assertCount(0, Post::search('user.tags.title', 'other')->get());
    }

    public function addTag($related, $tag)
    {
        DB::table('taggables')->insert([
            'taggable_type' => get_class($related),
            'taggable_id'   => $related->id,
            'tag_id'        => $tag->id,
        ]);
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
