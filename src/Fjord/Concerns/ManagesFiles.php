<?php

namespace AwStudio\Fjord\Fjord\Concerns;

trait ManagesFiles
{
    protected $cssFiles = [];

    public function addCssFile(string $path)
    {
        if(in_array($path, $this->cssFiles)) {
            return;
        }

        $this->cssFiles []= $path;
    }

    public function getCssFiles()
    {
        return $this->cssFiles;
    }
}
