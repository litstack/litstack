<?php

namespace Fjord\Crud\Fields\Block;

use Fjord\Crud\BaseForm;
use InvalidArgumentException;

class BlockForm extends BaseForm
{
    /**
     * Laravel relations not allowed for block.
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function relation(string $name)
    {
        throw new InvalidArgumentException('Laravel relations are not available in Block. Use fields oneRelation or manyRelation instead.');
    }
}
