<?php

namespace Ignite\Crud\Actions;

class RequestMock
{
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function decodedPath()
    {
        return rtrim(parse_url($this->url)['path'] ?? '', '/');
    }

    public function getHost()
    {
        return parse_url($this->url)['host'] ?? '';
    }
}
