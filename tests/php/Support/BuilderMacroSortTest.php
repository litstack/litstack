<?php

namespace Tests;

use BadMethodCallException;
use Ignite\Crud\Models\FormRelation;
use Ignite\Support\Macros\BuilderSort;
use Tests\TestSupport\Models\Post;
use Tests\TestSupport\Models\TranslatablePost;
use Tests\TestSupport\Models\User;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class BuilderMacroSortTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->macro = new BuilderSort();
        $this->macro->setQuery(DummyModel::query());
        $this->installLit();
        $this->migrate();
    }

    /** @test */
    public function test_isRelatedColum_function()
    {
        $class = new ReflectionClass($this->macro);
        $isRelatedColumn = $class->getMethod('isRelatedColumn');
        $isRelatedColumn->setAccessible(true);

        $this->assertTrue($isRelatedColumn->invokeArgs($this->macro, ['posts.title']));
        $this->assertFalse($isRelatedColumn->invokeArgs($this->macro, ['title']));
    }

    /** @test */
    public function it_sorts_by_normal_attribute()
    {
        Post::firstOrCreate(['text' => 'a']);
        Post::firstOrCreate(['text' => 'b']);

        // Using default direction.
        $posts = Post::sort('text')->get();
        $this->assertCount(2, $posts);
        $this->assertEquals('a', $posts[0]->text);
        $this->assertEquals('b', $posts[1]->text);

        // Using asc direction.
        $posts = Post::sort('text', 'asc')->get();
        $this->assertCount(2, $posts);
        $this->assertEquals('a', $posts[0]->text);
        $this->assertEquals('b', $posts[1]->text);

        // Using desc direction.
        $posts = Post::sort('text', 'desc')->get();
        $this->assertCount(2, $posts);
        $this->assertEquals('b', $posts[0]->text);
        $this->assertEquals('a', $posts[1]->text);
    }

    /** @test */
    public function it_sorts_by_belongsTo_attribute()
    {
        $user1 = User::firstOrCreate(['name' => 'user 1']);
        $user2 = User::firstOrCreate(['name' => 'user 2']);
        $user3 = User::firstOrCreate(['name' => 'user 3']);
        Post::firstOrCreate(['text' => 'post a', 'user_id' => $user1->id]);
        Post::firstOrCreate(['text' => 'post b', 'user_id' => $user3->id]);
        Post::firstOrCreate(['text' => 'post c', 'user_id' => $user2->id]);

        // Using default direction.
        $posts = Post::sort('user.name')->get();
        $this->assertCount(3, $posts);
        $this->assertEquals('user 1', $posts[0]->user->name);
        $this->assertEquals('user 2', $posts[1]->user->name);
        $this->assertEquals('user 3', $posts[2]->user->name);

        // Using asc direction.
        $posts = Post::sort('user.name', 'asc')->get();
        $this->assertCount(3, $posts);
        $this->assertEquals('user 1', $posts[0]->user->name);
        $this->assertEquals('user 2', $posts[1]->user->name);
        $this->assertEquals('user 3', $posts[2]->user->name);

        // Using desc direction.
        $posts = Post::sort('user.name', 'desc')->get();
        $this->assertCount(3, $posts);
        $this->assertEquals('user 3', $posts[0]->user->name);
        $this->assertEquals('user 2', $posts[1]->user->name);
        $this->assertEquals('user 1', $posts[2]->user->name);
    }

    /** @test */
    public function it_sorts_by_hasMany_attribute()
    {
        $user1 = User::firstOrCreate(['name' => 'user 1']);
        $user2 = User::firstOrCreate(['name' => 'user 2']);
        $user3 = User::firstOrCreate(['name' => 'user 3']);
        Post::firstOrCreate(['text' => 'post a', 'user_id' => $user1->id]);
        Post::firstOrCreate(['text' => 'post b', 'user_id' => $user3->id]);
        Post::firstOrCreate(['text' => 'post c', 'user_id' => $user2->id]);

        // Using default direction.
        $users = User::sort('post.text')->get();
        $this->assertCount(3, $users);
        $this->assertEquals('post a', $users[0]->post->text);
        $this->assertEquals('post b', $users[1]->post->text);
        $this->assertEquals('post c', $users[2]->post->text);

        // Using asc direction.
        $users = User::sort('post.text', 'asc')->get();
        $this->assertCount(3, $users);
        $this->assertEquals('post a', $users[0]->post->text);
        $this->assertEquals('post b', $users[1]->post->text);
        $this->assertEquals('post c', $users[2]->post->text);

        // Using desc direction.
        $users = User::sort('post.text', 'desc')->get();
        $this->assertCount(3, $users);
        $this->assertEquals('post c', $users[0]->post->text);
        $this->assertEquals('post b', $users[1]->post->text);
        $this->assertEquals('post a', $users[2]->post->text);
    }

    /** @test */
    public function it_throws_correct_exception_when_relation_doesnt_exist()
    {
        $this->expectException(BadMethodCallException::class);
        User::sort('not_exists.text', 'desc')->get();
    }

    /** @test */
    public function it_sorty_by_translated_attribute()
    {
        TranslatablePost::create([])->update([
            'en' => ['text' => 'en a'],
            'de' => ['text' => 'de a'],
        ]);
        TranslatablePost::create([])->update([
            'en' => ['text' => 'en b'],
            'de' => ['text' => 'de b'],
        ]);

        // Using default direction and "en" locale.
        $this->app->setLocale('en', 'asc');
        $posts = TranslatablePost::sort('text')->get();
        $this->assertCount(2, $posts);
        $this->assertEquals('en a', $posts[0]->text);
        $this->assertEquals('en b', $posts[1]->text);

        // Using default direction and "de" locale.
        $this->app->setLocale('de', 'asc');
        $posts = TranslatablePost::sort('text')->get();
        $this->assertCount(2, $posts);
        $this->assertEquals('de a', $posts[0]->text);
        $this->assertEquals('de b', $posts[1]->text);

        // Using default direction and "en" locale.
        $this->app->setLocale('en');
        $posts = TranslatablePost::sort('text', 'desc')->get();
        $this->assertCount(2, $posts);
        $this->assertEquals('en b', $posts[0]->text);
        $this->assertEquals('en a', $posts[1]->text);

        // Using default direction and "de" locale.
        $this->app->setLocale('de');
        $posts = TranslatablePost::sort('text', 'desc')->get();
        $this->assertCount(2, $posts);
        $this->assertEquals('de b', $posts[0]->text);
        $this->assertEquals('de a', $posts[1]->text);
    }

    public function it_sorts_by_related_translated_attribute()
    {
        // TODO make it work for hasOneThrough relation
        /*
        $user1 = User::firstOrCreate(['name' => 'user 1']);
        $user2 = User::firstOrCreate(['name' => 'user 2']);
        $user3 = User::firstOrCreate(['name' => 'user 3']);
        Post::firstOrCreate(['text' => 'post a', 'user_id' => $user1->id]);
        Post::firstOrCreate(['text' => 'post b', 'user_id' => $user3->id]);
        Post::firstOrCreate(['text' => 'post c', 'user_id' => $user2->id]);
        */
        $basePost1 = TranslatablePost::create([]);
        $basePost1->update([
            'en' => ['text' => 'en base 1'],
            'de' => ['text' => 'de base 1'],
        ]);
        $basePost2 = TranslatablePost::create([]);
        $basePost2->update([
            'en' => ['text' => 'en base 2'],
            'de' => ['text' => 'de base 2'],
        ]);
        $basePost3 = TranslatablePost::create([]);
        $basePost3->update([
            'en' => ['text' => 'en base 2'],
            'de' => ['text' => 'de base 2'],
        ]);
        $relatedPost1 = TranslatablePost::create([]);
        $relatedPost1->update([
            'en' => ['text' => 'en related a'],
            'de' => ['text' => 'de related a'],
        ]);
        $relatedPost2 = TranslatablePost::create([]);
        $relatedPost2->update([
            'en' => ['text' => 'en related b'],
            'de' => ['text' => 'de related b'],
        ]);
        $relatedPost3 = TranslatablePost::create([]);
        $relatedPost3->update([
            'en' => ['text' => 'en related c'],
            'de' => ['text' => 'de related c'],
        ]);

        factory(FormRelation::class, 1)->create([
            'name' => 'translatablePost',
            'from' => $basePost3,
            'to'   => $relatedPost1,
        ]);
        factory(FormRelation::class, 1)->create([
            'name' => 'translatablePost',
            'from' => $basePost2,
            'to'   => $relatedPost2,
        ]);
        factory(FormRelation::class, 1)->create([
            'name' => 'translatablePost',
            'from' => $basePost1,
            'to'   => $relatedPost3,
        ]);

        $posts = TranslatablePost::whereHas('translatablePost')->sort('translatablePost.text')->get();
        $this->assertCount(3, $posts);
        $this->assertEquals('post a', $posts[0]->post->text);
        $this->assertEquals('post b', $posts[1]->post->text);
        $this->assertEquals('post c', $posts[2]->post->text);
    }
}

class DummyRelationModel extends Model
{
}

class DummyModel extends Model
{
    public function posts()
    {
        return $this->hasMany(DummyRelationModel::class);
    }
}
