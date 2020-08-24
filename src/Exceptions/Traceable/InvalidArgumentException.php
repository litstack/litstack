<?php

namespace Lit\Exceptions\Traceable;

use Lit\Contracts\Exceptions\Traceable;
use Lit\Support\TraceableException;
use InvalidArgumentException as BaseInvalidArgumentException;

class InvalidArgumentException extends BaseInvalidArgumentException implements Traceable
{
    use TraceableException;
}
