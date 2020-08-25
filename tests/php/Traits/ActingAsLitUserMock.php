<?php

namespace Tests\Traits;

use Ignite\User\Models\User;
use Mockery as m;

trait ActingAsLitUserMock
{
    public function ActingAsLitUserMock()
    {
        $this->admin = m::mock(User::class)->makePartial();
        $this->actingAs($this->admin, 'lit');
    }
}
