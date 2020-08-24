<?php

namespace Tests\Support;

use Lit\Support\IndexTable;
use Tests\BackendTestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Mockery as m;

class IndexTableTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->request = m::mock(Request::class);
        $this->query = m::mock(DummyModel::query());
        $this->indexTable = new IndexTable($this->query, $this->request);
    }

    // Sort

    /** @test */
    public function it_doesnt_apply_sort_to_query_builder_when_sort_by_is_empty()
    {
        $querySpy = m::spy(DummyModel::query());
        $indexTable = new IndexTable($querySpy, $this->request);
        $this->request->sort_by = null;
        $this->callUnaccessibleMethod($indexTable, 'applySortToQuery', []);
        $querySpy->shouldNotHaveReceived('sort');
    }

    // Sort

    /** @test */
    public function it_applies_correct_values_to_sort_method()
    {
        // normal column without direction
        $this->request->sort_by = 'dummy_column';
        $this->query->shouldReceive('sort')->with('dummy_column', 'asc')->once();
        $this->callUnaccessibleMethod($this->indexTable, 'applySortToQuery', []);

        // normal column with direction
        $this->request->sort_by = 'dummy_column.desc';
        $this->query->shouldReceive('sort')->with('dummy_column', 'desc')->once();
        $this->callUnaccessibleMethod($this->indexTable, 'applySortToQuery', []);

        // normal column with relation and desc direction
        $this->request->sort_by = 'dummy_relation.dummy_column.desc';
        $this->query->shouldReceive('sort')->with('dummy_relation.dummy_column', 'desc')->once();
        $this->callUnaccessibleMethod($this->indexTable, 'applySortToQuery', []);
        // normal column with relation and asc direction
        $this->request->sort_by = 'dummy_relation.dummy_column.asc';
        $this->query->shouldReceive('sort')->with('dummy_relation.dummy_column', 'asc')->once();
        $this->callUnaccessibleMethod($this->indexTable, 'applySortToQuery', []);
    }

    // Search

    /** @test */
    public function it_doesnt_apply_search_to_query_builder_when_search_by_is_empty()
    {
        $querySpy = m::spy(DummyModel::query());
        $indexTable = new IndexTable($querySpy, $this->request);
        $this->request->search = null;
        $this->callUnaccessibleMethod($indexTable, 'applySearchToQuery', []);
        $querySpy->shouldNotHaveReceived('search');
    }

    // Search

    /** @test */
    public function it_can_set_search_keys_using_search_method()
    {
        $this->indexTable->search(['column1', 'column2']);
        $this->assertEquals(['column1', 'column2'], $this->indexTable->getSearchKeys());
    }

    // Search

    /** @test */
    public function it_applies_correct_values_to_search_method()
    {
        // normal column
        $this->indexTable->search(['dummy_column_name']);
        $this->request->search = 'dummy_search_term';
        $this->query->shouldReceive('search')->with(['dummy_column_name'], 'dummy_search_term')->once();
        $this->callUnaccessibleMethod($this->indexTable, 'applySearchToQuery', []);
    }

    // Filter

    /** @test */
    public function it_doesnt_apply_filter_to_query_builder_when_filter_by_is_empty()
    {
        $querySpy = m::spy(DummyModel::query());
        $indexTable = new IndexTable($querySpy, $this->request);
        $this->request->filter = null;
        $this->callUnaccessibleMethod($indexTable, 'applyFilterToQuery', []);
        $querySpy->shouldNotHaveReceived('*');
    }

    // Filter

    /** @test */
    public function it_calls_model_scope_for_when_filter_exists()
    {
        $this->request->filter = 'dummy_scope';
        $this->query->shouldReceive('dummy_scope')->once();
        $this->callUnaccessibleMethod($this->indexTable, 'applyFilterToQuery', []);
    }
}

class DummyModel extends Model
{
}
