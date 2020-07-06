<?php

namespace Fjord\Exceptions\Traceable;

use BadMethodCallException as BaseBadMethodCallException;
use Fjord\Contracts\Exceptions\Traceable;
use Fjord\Support\TraceableException;

class BadMethodCallException extends BaseBadMethodCallException implements Traceable
{
    use TraceableException;
}
