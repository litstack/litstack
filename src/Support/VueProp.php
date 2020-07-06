<?php

namespace Fjord\Support;

use Fjord\Vue\Traits\RenderableAsProp;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class VueProp implements Arrayable, Jsonable
{
    use RenderableAsProp;
}
