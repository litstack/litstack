<?php

namespace FjordTest\Traits;

use Fjord\User\Models\FjordUser;
use Mockery as m;

trait ActingAsFjordUserMock
{
    public function actingAsFjordUserMock()
    {
        $this->admin = m::mock(FjordUser::class)->makePartial();
        $this->actingAs($this->admin, 'fjord');
    }
}
