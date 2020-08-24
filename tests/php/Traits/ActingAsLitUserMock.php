<?php

namespace Tests\Traits;

use Lit\User\Models\LitUser;
use Mockery as m;

trait ActingAsLitUserMock
{
    public function actingAsLitUserMock()
    {
        $this->admin = m::mock(LitUser::class)->makePartial();
        $this->actingAs($this->admin, 'lit');
    }
}
