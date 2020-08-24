<?php

namespace Lit\Exceptions\Traceable;

use Lit\Contracts\Exceptions\Traceable;
use Lit\Support\TraceableException;
use LogicException;

class MissingAttributeException extends LogicException implements Traceable
{
    use TraceableException;
}
