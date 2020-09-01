<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Ignite\Crud\Models\Relation;

$factory->define(Relation::class, function (Faker $faker, $args) {
    if (! array_key_exists('name', $args)) {
        throw new InvalidArgumentException('Missing parameter "name".');
    }
    if (! array_key_exists('from', $args)) {
        throw new InvalidArgumentException('Missing parameter "from".');
    }
    if (! array_key_exists('to', $args)) {
        throw new InvalidArgumentException('Missing parameter "to".');
    }

    return [
        'field_id'        => $args['name'],
        'from_model_id'   => $args['from']->id,
        'from_model_type' => get_class($args['from']),
        'to_model_id'     => $args['to']->id,
        'to_model_type'   => get_class($args['to']),
    ];
});

$factory->afterMaking(Relation::class, function (Relation $relation) {
    $attributes = $relation->getAttributes();
    unset($attributes['name']);
    unset($attributes['from']);
    unset($attributes['to']);
    $relation->setRawAttributes($attributes);
});
