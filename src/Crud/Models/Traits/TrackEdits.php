<?php

namespace Fjord\Crud\Models\Traits;

use Fjord\Crud\Models\ModelEdit;

trait TrackEdits
{
    /**
     * Track edit on update.
     *
     * @param array $attributes
     * @param array $options
     */
    public function update(array $attributes = [], array $options = [])
    {
        $update = parent::update($attributes, $options);

        $this->edited();

        return $update;
    }

    /**
     * Store edit to database.
     *
     * @return void
     */
    public function edited($action = 'update')
    {
        if (!fjord_user()) {
            return;
        }

        $edit = new ModelEdit();
        $edit->model_type = static::class;
        $edit->model_id = $this->id;
        $edit->fjord_user_id = fjord_user()->id;
        $edit->created_at = \Carbon\Carbon::now();
        $edit->payload = ['action' => $action];
        $edit->save();

        // Reload relation.
        if ($this->relationLoaded('last_edit')) {
            $this->load('last_edit');
        }
    }

    /**
     * Get last edit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function last_edit()
    {
        return $this->morphOne(ModelEdit::class, 'model')
            ->orderByDesc('id')
            ->with('user');
    }
}
