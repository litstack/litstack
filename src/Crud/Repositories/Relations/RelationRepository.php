<?php

namespace Ignite\Crud\Repositories\Relations;

use Ignite\Crud\Repositories\BaseFieldRepository;
use Illuminate\Database\Eloquent\Model;

abstract class RelationRepository extends BaseFieldRepository
{
    /**
     * Link two models.
     *
     * @param  Model $from
     * @param  Model $to
     * @return void
     */
    abstract public function link(Model $from, Model $to);
}
