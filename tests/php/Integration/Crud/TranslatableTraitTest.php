<?php

namespace Tests\Integration\Crud;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Ignite\Crud\Models\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\BackendTestCase;

class TranslatableTraitTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
        });
        Schema::create('post_translations', function (Blueprint $table) {
            $table->translates('posts');
            $table->string('slug')->nullable();
        });
    }

    public function tearDown(): void
    {
        Schema::drop('posts');
        parent::tearDown();
    }

    /** @test */
    public function test_implicit_route_binding_of_translated_attributes()
    {
        $post1 = DummyTranslatablePostModel::create([
            'en' => ['slug' => 'hello'],
            'de' => ['slug' => 'hallo'],
        ]);

        $post2 = DummyTranslatablePostModel::create([
            'en' => ['slug' => 'foo'],
            'de' => ['slug' => 'bar'],
        ]);
        $post3 = DummyTranslatablePostModel::create([
            'en' => ['slug' => 'baz'],
            'de' => ['slug' => 'foo'],
        ]);

        Route::get('/{post:slug}', fn (DummyTranslatablePostModel $post) => $post->id)
            ->middleware(SubstituteBindings::class);

        app()->setLocale('en');
        $this->assertEquals($post1->id, $this->get('/hello')->assertStatus(200)->getContent());
        $this->assertEquals($post2->id, $this->get('/foo')->assertStatus(200)->getContent());
        $this->get('/bar')->assertStatus(404);

        app()->setLocale('de');
        $this->assertEquals($post1->id, $this->get('/hallo')->assertStatus(200)->getContent());
        $this->assertEquals($post3->id, $this->get('/foo')->assertStatus(200)->getContent());
        $this->get('/bar')->assertStatus(200);
    }
}

class DummyTranslatablePostModel extends Model implements TranslatableContract
{
    use Translatable;

    public $table = 'posts';
    protected $translatedAttributes = ['slug'];
    protected $fillable = ['title'];
    protected $translationModel = DummyTranslatablePostTranslationModel::class;
    protected $translationForeignKey = 'post_id';
    public $timestamps = false;
}

class DummyTranslatablePostTranslationModel extends Model
{
    public $table = 'post_translations';
    public $timestamps = false;
    protected $fillable = ['slug'];
}
