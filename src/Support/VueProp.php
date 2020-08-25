<?php

namespace Ignite\Support;

use Ignite\Contracts\Vue\Renderable;
use Ignite\Vue\Traits\RenderableAsProp;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class VueProp implements Arrayable, Jsonable, Renderable
{
    use RenderableAsProp;
}
