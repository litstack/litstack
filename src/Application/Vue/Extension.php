<?php

namespace Fjord\Application\Vue;

use Fjord\User\Models\FjordUser;

abstract class Extension
{
    /**
     * Extension name.
     *
     * @var string
     */
    protected $name;

    /**
     * Has user permission for this extension.
     *
     * @var \Fjord\User\Models\FjordUser $user
     *
     * @return bool
     */
    abstract public function authenticate(FjordUser $user);

    /**
     * Modify props in handle method.
     *
     * @var array $props
     *
     * @return array $props
     */
    abstract public function handle($props);

    /**
     * Create new Extension instance.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get extension name.
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }
}
