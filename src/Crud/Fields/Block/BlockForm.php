<?php

namespace Lit\Crud\Fields\Block;

use Lit\Crud\BaseForm;
use InvalidArgumentException;

class BlockForm extends BaseForm
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
