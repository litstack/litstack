<?php

namespace Fjord\Exceptions\Traceable;

use Fjord\Contracts\Exceptions\Traceable;
use Fjord\Support\TraceableException;
use InvalidArgumentException as BaseInvalidArgumentException;

class InvalidArgumentException extends BaseInvalidArgumentException implements Traceable
{
    use TraceableException;
}
