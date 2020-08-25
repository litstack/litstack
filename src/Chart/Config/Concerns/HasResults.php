<?php

namespace Ignite\Chart\Config\Concerns;

use Closure;
use Illuminate\Support\Collection;

trait HasResults
{
    /**
     * Result resolver.
     *
     * @var Closure
     */
    protected $resultResolver;

    /**
     * Number that is displayed at the bottom left corner.
     *
     * @param \Illuminate\Support\Collection
     *
     * @return int
     */
    public function result(Collection $values): int
    {
        if ($this->resultResolver instanceof Closure) {
            $resolver = $this->resultResolver;

            return $resolver($values);
        }

        return 0;
    }
}
