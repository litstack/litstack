<?php

namespace Tests\Integration\Page;

use Ignite\Page\Table\Table;
use Tests\BackendTestCase;

class TableTest extends BackendTestCase
{
    /** @test */
    public function test_alphabeticOrder_method()
    {
        $this->assertEquals([
            'title.asc'  => 'A -> Z',
            'title.desc' => 'Z -> A',
        ], Table::alphabeticOrder());

        $this->assertEquals([
            'foo.asc'  => 'A -> Z',
            'foo.desc' => 'Z -> A',
        ], Table::alphabeticOrder('foo'));
    }

    /** @test */
    public function test_numericOrder_method()
    {
        $this->assertEquals([
            'id.desc' => 'New first',
            'id.asc'  => 'Old first',
        ], Table::numericOrder());

        $this->assertEquals([
            'foo.desc' => 'New first',
            'foo.asc'  => 'Old first',
        ], Table::numericOrder('foo'));
    }
}
