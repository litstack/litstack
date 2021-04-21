<?php

namespace Tests\Crud;

use Ignite\Crud\FormResource;
use Ignite\Crud\Models\Media;
use Ignite\Crud\Models\Repeatable;
use Illuminate\Support\Facades\Storage;
use PDOException;
use Tests\BackendTestCase;
use Tests\Crud\Fixtures\DummyLitFormModel;

class LitFormModelTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        DummyLitFormModel::schemaUp();
    }

    public function tearDown(): void
    {
        DummyLitFormModel::schemaDown();
        parent::tearDown();
    }

    /** @test */
    public function test_resource_method_returns_crud_resource()
    {
        $this->assertInstanceOf(FormResource::class, DummyLitFormModel::create()->resource());
    }

    /** @test */
    public function it_stores_field_values_to_value_column()
    {
        $model = new DummyLitFormModel([
            'config_type' => Fixtures\ConfigWithBlockField::class,
        ]);
        $model->save();

        $model->update([
            'foo' => 'bar',
        ]);

        $this->assertEquals(['foo' => 'bar'], $model->refresh()->value);
    }

    /** @test */
    public function it_works_for_lit_relations()
    {
        $model = new DummyLitFormModel([
            'config_type' => Fixtures\ConfigWithLitRelations::class,
        ]);
        $model->save();
    }

    /** @test */
    public function it_translatable_field_values_to_translatable_value_column()
    {
        $this->app['config']->set('translatable.locales', ['en', 'de']);

        $model = new DummyLitFormModel([
            'config_type' => Fixtures\ConfigWithTwoTranslatableInputFormFields::class,
        ]);
        $model->save();

        $model->update([
            'en' => ['foo' => 'en-bar'],
            'de' => ['foo' => 'de-bar'],
        ]);

        $this->assertEquals(['foo' => 'en-bar'], $model->translations()->whereLocale('en')->first()->value);
        $this->assertEquals(['foo' => 'de-bar'], $model->translations()->whereLocale('de')->first()->value);
    }

    /** @test */
    public function it_receives_repeatables()
    {
        $model = new DummyLitFormModel([
            'config_type' => Fixtures\ConfigWithBlockField::class,
        ]);
        $model->save();

        $repeatable = new Repeatable();
        $repeatable->type = 'text';
        $repeatable->model_type = get_class($model);
        $repeatable->model_id = $model->id;
        $repeatable->field_id = 'content';
        $repeatable->config_type = $model->config_type;
        $repeatable->form_type = 'show';
        $repeatable->value = ['text' => 'foo'];
        $repeatable->order_column = 0;
        $repeatable->save();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $model->content);
        $this->assertCount(1, $model->content);
        $this->assertInstanceOf(Repeatable::class, $model->content->first());
        $this->assertEquals($repeatable->id, $model->content->first()->id);
    }

    /** @test */
    public function it_receives_images()
    {
        Storage::fake();

        $model = new DummyLitFormModel([
            'config_type' => Fixtures\ConfigWithMediaField::class,
        ]);
        $model->save();

        $media = new Media;
        $media->collection_name = 'images';
        $media->name = 'foo';
        $media->file_name = 'foo';
        $media->disk = 'foo';
        $media->manipulations = 'foo';
        $media->responsive_images = 'foo';
        $media->custom_properties = [];
        $media->size = 0;
        $media->model_type = DummyLitFormModel::class;
        $media->model_id = $model->id;
        $media->fill([
            'generated_conversions' => 'foo',
        ]);

        try {
            $media->save();
        } catch (PDOException $e) {
            $this->markTestSkipped('Skipping for spatie/media-library < ^9.0');
        }

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $model->images);
        $this->assertCount(1, $model->images);
        $this->assertInstanceOf(Media::class, $model->images->first());
        $this->assertEquals($media->id, $model->images->first()->id);
    }

    /** @test */
    public function it_receives_single_image_not_as_collection()
    {
        Storage::fake();

        $model = new DummyLitFormModel([
            'config_type' => Fixtures\ConfigWithMediaField::class,
        ]);
        $model->save();

        $media = new Media();
        $media->collection_name = 'image';
        $media->name = 'foo';
        $media->file_name = 'foo';
        $media->disk = 'foo';
        $media->manipulations = 'foo';
        $media->responsive_images = 'foo';
        $media->custom_properties = [];
        $media->size = 0;
        $media->model_type = DummyLitFormModel::class;
        $media->model_id = $model->id;
        $media->fill([
            'generated_conversions' => 'foo',
        ]);

        try {
            $media->save();
        } catch (PDOException $e) {
            $this->markTestSkipped('Skipping for spatie/media-library < ^9.0');
        }

        $this->assertInstanceOf(Media::class, $model->image);
        $this->assertEquals($media->id, $model->image->id);
    }
}
