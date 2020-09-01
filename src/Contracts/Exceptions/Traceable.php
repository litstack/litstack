<?php

namespace Ignite\Contracts\Exceptions;

use Exception;

/**
 * The task of a traceable exception is to move a selected a trace to show the
 * user the relevant trace first and not the one where the exception has been
 * thrown.
 *
 * {$options} contain keys of the trace that should be moved to the beginning.
 */
interface Traceable
{
    /**
     * Create new Traceable instance.
     *
     * @param  string    $message
     * @param  array     $options
     * @param  int       $code
     * @param  Exception $previous
     * @return void
     */
    public function __construct($message = null, array $options = [], $code = 0, Exception $previous = null);

    /**
     * Set trace by options.
     *
     * @param  array $options
     * @return void
     */
    public function setTrace($options);
}
