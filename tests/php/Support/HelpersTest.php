<?php

namespace FjordTest\Support;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Fjord\Crud\Models\Traits\Translatable;
use FjordTest\BackendTestCase;
use Illuminate\Database\Eloquent\Model;

class HelpersTest extends BackendTestCase
{
    /** @test */
    public function test_is_translatale_method()
    {
        $this->assertFalse(is_translatable(HelpersNonTranslatableModel::class));
        $this->assertFalse(is_translatable(new HelpersNonTranslatableModel()));
        $this->assertTrue(is_translatable(HelpersTranslatableModel::class));
        $this->assertTrue(is_translatable(new HelpersTranslatableModel()));
    }

    /** @test */
    public function test_is_attribute_translatale_method()
    {
        $this->assertFalse(is_attribute_translatable('some_attribute', HelpersNonTranslatableModel::class));
        $this->assertFalse(is_attribute_translatable('some_attribute', HelpersTranslatableModel::class));
        $this->assertTrue(is_attribute_translatable('translated_attribute', HelpersTranslatableModel::class));
    }
}

class HelpersNonTranslatableModel extends Model
{
}

class HelpersTranslatableModel extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['translated_attribute'];
}
