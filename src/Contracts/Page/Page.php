<?php

namespace Fjord\Contracts\Page;

interface Page
{
    public function view($view);

    public function component($name);

    public function bind(array $data);

    public function bindToView(array $data);

    public function bindToVue(array $props);
}
