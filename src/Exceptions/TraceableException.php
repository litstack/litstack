<?php

namespace Fjord\Exceptions;

use Exception;
use Fjord\Exceptions\Contracts\Traceable;

class TraceableException extends Exception implements Traceable
{
    /**
     * Create new TraceableException instance.
     *
     * @param string    $message
     * @param array     $options
     * @param int       $code
     * @param Exception $previous
     */
    public function __construct($message = null, array $options = [], $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->setTrace(
            $this->findTrace($options)
        );
    }

    /**
     * Set trace.
     *
     * @param  array $trace
     * @return void
     */
    public function setTrace($trace)
    {
        if (! $trace) {
            return;
        }

        if (! array_key_exists('line', $trace)) {
            return;
        }

        if (! array_key_exists('file', $trace)) {
            return;
        }

        $this->line = $trace['line'];
        $this->file = $trace['file'];
    }

    /**
     * Find trace by options.
     *
     * @param  array      $options
     * @return array|null
     */
    public function findTrace(array $options)
    {
        if (empty($options)) {
            return;
        }

        foreach ($this->getTrace() as $trace) {
            if (! array_key_exists('class', $trace)) {
                continue;
            }

            if ($this->hasTraceOptions($trace, $options)) {
                return $trace;
            }
        }
    }

    /**
     * Has trace options.
     *
     * @param  array $trace
     * @param  array $options
     * @return bool
     */
    protected function hasTraceOptions($trace, $options)
    {
        foreach ($options as $key => $value) {
            if (! array_key_exists($key, $trace)) {
                false;
            }

            if ($trace[$key] != $value) {
                return false;
            }
        }

        return true;
    }
}
