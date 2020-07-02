<?php

namespace Fjord\Exceptions\Traceable;

use Fjord\Contracts\Exceptions\Traceable;
use Fjord\Support\TraceableException;
use LogicException;

class MissingAttributeException extends LogicException implements Traceable
{
    use TraceableException;
}
