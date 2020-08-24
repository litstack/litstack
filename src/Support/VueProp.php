<?php

namespace Lit\Support;

use Lit\Contracts\Vue\Renderable;
use Lit\Vue\Traits\RenderableAsProp;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class VueProp implements Arrayable, Jsonable, Renderable
{
    use RenderableAsProp;
}
