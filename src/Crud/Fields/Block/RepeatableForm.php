<?php

namespace Ignite\Crud\Fields\Block;

use Ignite\Crud\BaseForm;
use InvalidArgumentException;

class RepeatableForm extends BaseForm
{
    /**
     * Laravel relations not allowed for block.
     *
     * @param  string $name
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function relation(string $name)
    {
        throw new InvalidArgumentException('Laravel relations are not available in Block. Use fields oneRelation or manyRelation instead.');
    }
}
