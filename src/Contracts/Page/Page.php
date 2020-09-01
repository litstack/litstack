<?php

namespace Ignite\Contracts\Page;

interface Page
{
    /**
     * Add Blade View to page.
     *
     * @param  string $view
     * @return void
     */
    public function view($view);

    /**
     * Adds Vue component to page.
     *
     * @param  string                $name
     * @return \Ignite\Vue\Component
     */
    public function component($name);

    /**
     * Binds data to Vue components and Blade Views.
     *
     * @param  array $data
     * @return void
     */
    public function bind(array $data);

    /**
     * Binds data to Blade Views.
     *
     * @param  array $data
     * @return void
     */
    public function bindToView(array $data);

    /**
     * Bind props to Vue components.
     *
     * @param  array $props
     * @return void
     */
    public function bindToVue(array $props);
}
