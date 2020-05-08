<?php

namespace Fjord\Crud\Exceptions;

use Throwable;
use Fjord\Crud\Field;

trait ThrowsFieldException
{
    /**
     * Throw Field exception.
     *
     * @param string $message
     * @return void
     * 
     * @throws Throwable
     */
    public function throwFieldException(Throwable $e)
    {
        if (!$e instanceof FieldException) {
            throw $e;
        }

        $trace = $this->findFieldExceptionTrace($e);

        if ($trace === null) {
            throw $e;
        }

        $e->setTrace($trace);

        throw $e;
    }

    protected function findFieldExceptionTrace(Throwable $e)
    {
        $trace = $e->getTrace();
        foreach ($trace as $part) {
            if (!array_key_exists('function', $part)) {
                continue;
            }

            if (!array_key_exists('class', $part)) {
                continue;
            }

            if ($part['function'] == '__call' && $part['class'] == Field::class) {
                return $part;
            }
        }
    }
}
