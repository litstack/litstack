<?php

namespace FjordTest\Fields;

use Fjord\Crud\Fields\Media\Image;
use Fjord\Crud\Fields\Traits\HasBaseField;
use FjordTest\Traits\InteractsWithFields;
use FjordTest\Traits\TestHelpers;
use PHPUnit\Framework\TestCase;

class FieldImageTest extends TestCase
{
    use InteractsWithFields, TestHelpers;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Image::class);
    }

    /** @test */
    public function it_has_base_field()
    {
        $this->assertHasTrait(HasBaseField::class, $this->field);
    }

    /** @test */
    public function test_showFullImage_method()
    {
        $this->field->showFullImage();
        $this->assertArrayHasKey('showFullImage', $this->field->getAttributes());
        $this->assertEquals(true, $this->field->getAttribute('showFullImage'));

        $this->field->showFullImage(false);
        $this->assertArrayHasKey('showFullImage', $this->field->getAttributes());
        $this->assertEquals(false, $this->field->getAttribute('showFullImage'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->showFullImage());
    }

    /** @test */
    public function test_sortable_method()
    {
        $this->field->sortable();
        $this->assertArrayHasKey('sortable', $this->field->getAttributes());
        $this->assertEquals(true, $this->field->getAttribute('sortable'));

        $this->field->sortable(false);
        $this->assertArrayHasKey('sortable', $this->field->getAttributes());
        $this->assertEquals(false, $this->field->getAttribute('sortable'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->sortable());
    }

    /** @test */
    public function test_expand_method()
    {
        $this->field->expand();
        $this->assertArrayHasKey('expand', $this->field->getAttributes());
        $this->assertEquals(true, $this->field->getAttribute('expand'));

        $this->field->expand(false);
        $this->assertArrayHasKey('expand', $this->field->getAttributes());
        $this->assertEquals(false, $this->field->getAttribute('expand'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->expand());
    }

    /** @test */
    public function test_override_method()
    {
        $this->field->override();
        $this->assertArrayHasKey('override', $this->field->getAttributes());
        $this->assertEquals(true, $this->field->getAttribute('override'));

        $this->field->override(false);
        $this->assertArrayHasKey('override', $this->field->getAttributes());
        $this->assertEquals(false, $this->field->getAttribute('override'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->override());
    }

    /** @test */
    public function test_crop_method()
    {
        $this->field->crop(1 / 2);
        $this->assertArrayHasKey('crop', $this->field->getAttributes());
        $this->assertEquals(1 / 2, $this->field->getAttribute('crop'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->crop(1 / 2));
    }

    /** @test */
    public function test_firstBig_method()
    {
        $this->field->firstBig();
        $this->assertArrayHasKey('firstBig', $this->field->getAttributes());
        $this->assertEquals(true, $this->field->getAttribute('firstBig'));

        $this->field->firstBig(false);
        $this->assertArrayHasKey('firstBig', $this->field->getAttributes());
        $this->assertEquals(false, $this->field->getAttribute('firstBig'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->firstBig());
    }

    /** @test */
    public function test_maxFiles_method()
    {
        $this->field->maxFiles(4);
        $this->assertArrayHasKey('maxFiles', $this->field->getAttributes());
        $this->assertEquals(4, $this->field->getAttribute('maxFiles'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->maxFiles(1));
    }

    /** @test */
    public function test_maxFileSize_method()
    {
        $this->field->maxFileSize(14);
        $this->assertArrayHasKey('maxFileSize', $this->field->getAttributes());
        $this->assertEquals(14, $this->field->getAttribute('maxFileSize'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->maxFileSize(1));
    }
}
