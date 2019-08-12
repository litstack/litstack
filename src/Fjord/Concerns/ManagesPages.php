<?php

namespace AwStudio\Fjord\Fjord\Concerns;

trait ManagesPages
{
    protected $pages = [];

    public function pages()
    {
        return collect($this->pageFiles())->map(function($path) {
            return str_replace('.php', '', basename($path));
        });
    }

    public function pageFiles()
    {
        return glob(fjord_resource_path("pages/*.php"));
    }

    public function pageFilePath($title)
    {
        return fjord_resource_path("pages/{$title}.php");
    }

    public function getPage($title)
    {
        if(array_key_exists($title, $this->pages)) {
            return $this->pages[$title];
        }

        return $this->loadPage($title);
    }

    protected function loadPage($title)
    {
        if(! file_exists($this->pageFilePath($title))) {
            return [];
        }

        $page = require $this->pageFilePath($title);

        // Prepare Fields.
        if(array_key_exists('fields', $page)) {
            $page['fields'] = $this->prepareFields(
                $page['fields'],
                $this->pageFilePath($title),
                function($field) use ($page) {
                    return $this->preparePageField($field, $page);
                }
            );
        }

        $this->pages[$title] = $page;

        return $this->pages[$title];
    }

    protected function preparePageField($field, $page)
    {
        if(! $field->attributeExists('translatable')) {
            $field->setAttribute(
                'translatable',
                config('fjord.pages.translatable')
            );
        }

        return $field;
    }

    public function pageContent()
    {
        // TODO: Load Models
    }
}
