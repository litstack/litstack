<?php

namespace AwStudio\Fjord\Application\Concerns;

trait ManagesFiles
{
    /**
     * List of css Files that the Fjord application uses.
     *
     * @var array
     */
    protected $cssFiles = [];

    /**
     * Add css file to the Fjord application.
     *
     * @param string $path
     * @return void
     */
    public function addCssFile(string $path)
    {
        if (in_array($path, $this->cssFiles)) {
            return;
        }

        $this->cssFiles[] = $path;
    }

    /**
     * Get css files for Fjord application.
     *
     * @return array
     */
    public function getCssFiles()
    {
        return $this->cssFiles;
    }
}
