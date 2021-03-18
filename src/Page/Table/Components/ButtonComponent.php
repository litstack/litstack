<?php

namespace Ignite\Page\Table\Components;

use Ignite\Contracts\Page\Column;
use Ignite\Support\Vue\ButtonComponent as BaseButtonComponent;

class ButtonComponent extends BaseButtonComponent implements Column
{
    use Concerns\ManagesColumn;

    /**
     * Depndor property name.
     *
     * @var string
     */
    protected $dependor = 'item';
}
