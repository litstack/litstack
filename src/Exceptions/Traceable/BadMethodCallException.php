<?php

namespace Ignite\Exceptions\Traceable;

use BadMethodCallException as BaseBadMethodCallException;
use Ignite\Contracts\Exceptions\Traceable;
use Ignite\Support\TraceableException;

class BadMethodCallException extends BaseBadMethodCallException implements Traceable
{
    use TraceableException;
}
