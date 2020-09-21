<?php

namespace Ignite\Page\Table\Components;

use Ignite\Contracts\Page\Column;
use Ignite\Vue\Component;

class ColumnComponent extends Component implements Column
{
    use Concerns\ManagesColumn;
}
