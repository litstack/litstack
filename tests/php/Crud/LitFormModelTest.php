<?php

namespace Tests\Crud;

use Ignite\Crud\Models\Form;
use Spatie\Snapshots\MatchesSnapshots;
use Tests\BackendTestCase;

class LitFormModelTest extends BackendTestCase
{
    use MatchesSnapshots;

    /** @test */
    public function test_fieldsToArray_method()
    {
        $form = new Form();

        // Fill form with these fields: input, select, block, and list.
        // Needs help: how to fill the form?

        $this->assertMatchesSnapshot($form->fieldsToArray());
    }
}
