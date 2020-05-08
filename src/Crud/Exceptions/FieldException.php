<?php

namespace Fjord\Crud\Exceptions;

use Exception;

class FieldException extends Exception
{
    /**
     * Create new FieldException instance.
     *
     * @param string $message
     * @param integer $code
     * @param Exception $previous
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Set trace.
     *
     * @param array $trace
     * @return void
     */
    public function setTrace(array $trace)
    {
        if (!array_key_exists('line', $trace)) {
            return;
        }

        if (!array_key_exists('file', $trace)) {
            return;
        }

        $this->line = $trace['line'];
        $this->file = $trace['file'];
    }
}
