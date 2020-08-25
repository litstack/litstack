<?php

namespace Ignite\Contracts\Page;

interface Table
{
    public function singularName($name);

    public function pluralName($name);

    public function perPage(int $perPage);

    public function sortByDefault(string $attribute);

    public function search(...$keys);

    public function sortBy(...$keys);

    public function filter(array $filter);
}
