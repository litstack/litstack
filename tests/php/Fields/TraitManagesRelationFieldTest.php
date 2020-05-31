<?php

namespace FjordTest\Fields;

use Fjord\Crud\Field;
use FjordTest\BackendTestCase;
use Fjord\Support\Facades\Form;
use Illuminate\Database\Eloquent\Model;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Concerns\ManagesRelationField;

class TraitManagesRelationFieldTest extends BackendTestCase
{
    use InteractsWithFields;

    /** @test */
    public function test_getRelation_returns_relation_instance()
    {
        // TODO:
    }
}

class ManagesRelationFieldField extends Field
{
    use ManagesRelationField;

    public function relation()
    {
        //
    }
}

class ManagesRelationFieldModel extends Model
{
    public function relation()
    {
        return $this->hasMany(ManagesRelationFieldRelation::class);
    }
}

class ManagesRelationFieldRelation extends Model
{
}
