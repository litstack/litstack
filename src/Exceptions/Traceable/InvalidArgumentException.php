<?php

namespace Ignite\Exceptions\Traceable;

use Ignite\Contracts\Exceptions\Traceable;
use Ignite\Support\TraceableException;
use InvalidArgumentException as BaseInvalidArgumentException;

class InvalidArgumentException extends BaseInvalidArgumentException implements Traceable
{
    use TraceableException;
}
