<?php

namespace Fjord\Exceptions\Contracts;

use Exception;

interface Traceable
{
    public function getMessage();

    public function getCode();

    public function getFile();

    public function getLine();

    public function getTrace();

    public function getTraceAsString();

    public function __toString();

    public function __construct($message = null, array $options = [], $code = 0, Exception $previous = null);
}
