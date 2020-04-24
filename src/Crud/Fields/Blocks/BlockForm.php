<?php

namespace Fjord\Crud\Fields\Blocks;

use Fjord\Crud\BaseForm;
use InvalidArgumentException;

class BlockForm extends BaseForm
{
    /**
     * Laravel relations not allowed for blocks.
     *
     * @param string $name
     * @return mixed
     * 
     * @throws \InvalidArgumentException
     */
    public function relation(string $name)
    {
        throw new InvalidArgumentException("Laravel relations are not available in Blocks. Use fields oneRelation or manyRelation instead.");
    }
}
