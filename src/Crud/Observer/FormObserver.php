<?php

namespace Ignite\Crud\Observer;

use Ignite\Crud\Form as FormLoader;
use Ignite\Crud\Models\Form;
use Illuminate\Cache\CacheManager;

class FormObserver
{
    /**
     * CacheManager instance.
     *
     * @var CacheManager
     */
    protected $cache;

    /**
     * Form loader instance.
     *
     * @var Form
     */
    protected $loader;

    /**
     * Create new FormObserver instance.
     *
     * @param CacheManager $cache
     * @param FormLoader $loader
     * @return void
     */
    public function __construct(CacheManager $cache, FormLoader $loader)
    {
        $this->cache = $cache;
        $this->loader = $loader;
    }

    /**
     * Handle the Form "saved" event.
     *
     * @param  Form  $form
     * @return void
     */
    public function saved(Form $form)
    {
        $this->cache->forget($this->loader->getCacheKey());
        $this->cache->forget($this->loader->getCacheKey($form->collection));
        $this->cache->forget(
            $this->loader->getCacheKey($form->collection, $form->form_name)
        );
    }
}
