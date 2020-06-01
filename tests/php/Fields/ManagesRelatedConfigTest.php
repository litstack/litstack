<?php

namespace FjordTest\Fields;

use Fjord\Crud\Field;
use FjordTest\BackendTestCase;
use Illuminate\Database\Eloquent\Model;
use FjordTest\Traits\InteractsWithConfig;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Relations\Concerns\ManagesRelatedConfig;

class ManagesRelatedConfigTest extends BackendTestCase
{
    use InteractsWithFields, InteractsWithConfig;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(
            DummyRelatedConfigField::class,
            'dummy_relation',
            RelatedConfigFieldModel::class
        );
    }

    public function configExists(string $key)
    {
        return $key != 'crud.not_existing_config';
    }

    public function getConfig(string $key, ...$params)
    {
        return new RelatedConfigFieldRelationConfig;
    }

    /** @test */
    public function it_throws_exception_when_config_does_not_exist()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->field->loadRelatedConfig('not_existing_config');
    }

    /** @test */
    public function it_sets_config_attribute()
    {
        $this->field->loadRelatedConfig('dummy_any');

        $this->assertArrayHasKey('config', $this->field->getAttributes());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $this->field->getAttribute('config'));
    }
}

class RelatedConfigFieldRelationConfig
{
    public $names = ['singular' => 'dummy_name'];
    public $search = 'dummy_search';
    public $route_prefix = 'dummy_route_prefix';
    public $index = '';
}

class RelatedConfigFieldModel extends Model
{
}

class DummyRelatedConfigField extends Field
{
    use ManagesRelatedConfig;
}
