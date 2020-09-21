<?php

namespace Ignite\Page\Table\Components;

use Ignite\Contracts\Page\Column;
use Ignite\Support\Vue\DropdownComponent as BaseDropdownComponent;

class DropdownComponent extends BaseDropdownComponent implements Column
{
    use Concerns\ManagesColumn;
}
