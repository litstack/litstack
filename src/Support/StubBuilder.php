<?php

namespace Ignite\Support;

use File;
use Illuminate\Support\Str;

class StubBuilder
{
    protected $stubString = '';

    protected $attributes = [];

    protected $neededDummies = [];

    public function __construct($stub)
    {
        if (is_valid_path($stub)) {
            $this->loadStub($stub);
        } else {
            $this->setStubString($stub);
        }
    }

    public function setStubString(string $string)
    {
        $this->stubString = $string;

        $this->getDummyAttributes();
    }

    public function loadStub($path)
    {
        $this->setStubString(file_get_contents($path));
    }

    public function with(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $dummyKey = 'Dummy'.ucfirst($key);

            if (array_key_exists($dummyKey, $this->attributes)) {
                $this->attributes[$dummyKey] .= "\n$value";
            } else {
                $this->attributes[$dummyKey] = $value;
            }
        }
    }

    protected function getDummyAttributes()
    {
        preg_match_all('~(Dummy\w+)~', $this->stubString, $matches, PREG_PATTERN_ORDER);

        $this->neededDummies = $matches[0];
    }

    public function create($path)
    {
        $fileContents = $this->stubString;

        foreach ($this->neededDummies as $dummy) {
            if (! array_key_exists($dummy, $this->attributes)) {
                $fileContents = str_replace($dummy, '', $fileContents);
            } else {
                $fileContents = str_replace($dummy, $this->attributes[$dummy], $fileContents);
            }
        }

        if (File::exists($path)) {
            return false;
        }

        return File::put($path, $fileContents);
    }

    public function __call($method, $parameters = [])
    {
        if (Str::startsWith($method, 'with')) {
            $attributeName = lcfirst(str_replace('with', '', $method));

            $this->with([
                $attributeName => $parameters[0],
            ]);
        }
    }
}
