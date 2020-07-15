<?php

namespace Fjord\Contracts\Page;

use Illuminate\Contracts\View\View;

interface ColumnBuilder
{
    public function col($label = ''): Column;

    public function component($component): Column;

    public function view($view): View;

    public function image($label = '');

    public function relation($label = '');

    public function toggle($attribute);
}
