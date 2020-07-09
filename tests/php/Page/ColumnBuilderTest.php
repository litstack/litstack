<?php

namespace FjordTest\Page;

use Fjord\Page\Table\Column;
use Fjord\Page\Table\ColumnBuilder;
use FjordTest\BackendTestCase;

class ColumnBuilderTest extends BackendTestCase
{
    /** @test */
    public function test_col_method_returns_column()
    {
        $builder = new ColumnBuilder;
        $this->assertInstanceOf(Column::class, $builder->col());
    }

    /** @test */
    public function test_col_method_registers_column()
    {
        $builder = new ColumnBuilder;
        $builder->col();
        $columns = $this->getUnaccessibleProperty($builder, 'columns');
        $this->assertCount(1, $columns);
        $this->assertInstanceOf(Column::class, $columns[0]);
    }
}
