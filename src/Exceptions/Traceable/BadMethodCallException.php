<?php

namespace Lit\Exceptions\Traceable;

use BadMethodCallException as BaseBadMethodCallException;
use Lit\Contracts\Exceptions\Traceable;
use Lit\Support\TraceableException;

class BadMethodCallException extends BaseBadMethodCallException implements Traceable
{
    use TraceableException;
}
