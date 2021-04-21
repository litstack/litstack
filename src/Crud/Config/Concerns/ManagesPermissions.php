<?php

namespace Ignite\Crud\Config\Concerns;

trait ManagesPermissions
{
    /**
     * Get permissions.
     *
     * @return array
     */
    abstract public function permissions(): array;

    /**
     * Determines wether the config has permission for the given operation.
     *
     * @param  string $operation
     * @return bool
     */
    public function can($operation)
    {
        $permissions = $this->get()->permissions;

        return $permissions[$operation] ?? false;
    }
}
