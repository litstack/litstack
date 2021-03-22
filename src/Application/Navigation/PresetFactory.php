<?php

namespace Ignite\Application\Navigation;

use Closure;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class PresetFactory
{
    /**
     * Navigation presets.
     *
     * @var array
     */
    protected $presets = [];

    /**
     * Register navigation preset.
     *
     * @param  array|string $keys
     * @param  array        $entry
     * @return $this
     */
    public function preset($keys, array $entry)
    {
        $keys = Arr::wrap($keys);

        $this->presets[] = [
            'keys'  => $keys,
            'entry' => $entry,
        ];

        return $this;
    }

    /**
     * Get all navigation presets.
     *
     * @return array
     */
    public function all()
    {
        return $this->presets;
    }

    /**
     * Get preset for the given key and merge data to entry.
     *
     * @param  string $key
     * @param  array  $merge
     * @return array
     */
    public function getPreset($key, array $merge = [])
    {
        $preset = $this->findPresetByKey($key);

        if (! $preset) {
            throw new InvalidArgumentException("Couldn't find navigation preset for [$key].");
        }

        $preset = array_merge($preset, $merge);

        $title = $preset['title'] ?? null;

        if ($title instanceof Closure) {
            $preset['title'] = $preset['title']();
        }

        if ($preset['link'] instanceof Closure) {
            $preset['link'] = $preset['link']();
        }

        if (($preset['badge'] ?? null) instanceof Closure) {
            $preset['badge'] = $preset['badge']();
        }

        return $preset;
    }

    /**
     * Determines if preset for the given key exists.
     *
     * @param  string $key
     * @return bool
     */
    public function hasPreset($key)
    {
        return (bool) $this->findPresetByKey($key);
    }

    /**
     * Find navigation preset by the given key.
     *
     * @param  string     $desired
     * @return array|void
     */
    protected function findPresetByKey($desired)
    {
        foreach ($this->presets as $preset) {
            foreach ($preset['keys'] as $key) {
                if ($key == $desired) {
                    return $preset['entry'];
                }
            }
        }
    }
}
