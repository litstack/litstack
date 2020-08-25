<?php

namespace Ignite\Exceptions\Traceable;

use Ignite\Contracts\Exceptions\Traceable;
use Ignite\Support\TraceableException;
use LogicException;

class MissingAttributeException extends LogicException implements Traceable
{
    use TraceableException;
}
