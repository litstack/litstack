<?php

namespace Fjord\Crud\Fields\Media;

use Fjord\Crud\RelationField;

class MediaField extends RelationField
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-media';

    /**
     * Set sortable.
     *
     * @param bool $sortable
     * @return $this
     */
    public function sortable(bool $sortable = true)
    {
        $this->setAttribute('sortable', $sortable);

        return $this;
    }

    /**
     * Set override.
     *
     * @param bool $override
     * @return $this
     */
    public function override(bool $override = true)
    {
        $this->setAttribute('override', $override);

        return $this;
    }

    /**
     * Set max files.
     *
     * @param integer $maxFiles
     * @return $this
     */
    public function maxFiles(int $maxFiles)
    {
        $this->setAttribute('maxFiles', $maxFiles);

        return $this;
    }

    /**
     * Set max image size.
     *
     * @param integer $size
     * @return $this
     */
    public function fileSize(int $size)
    {
        $this->setAttribute('fileSize', $size);

        return $this;
    }

    /**
     * Accept mime types.
     *
     * @param string $mimeTypes
     * @return $this
     */
    public function accept($mimeTypes)
    {
        $this->setAttribute('accept', $mimeTypes);

        return $this;
    }

    /**
     * Get relation query for model.
     *
     * @param mixed $model
     * @param boolean $query
     * @return mixed
     */
    public function getRelationQuery($model)
    {
        return $model->media()->where('collection', $this->id);
    }

    /**
     * Get results.
     *
     * @param mixed $model
     * @return mixed
     */
    public function getResults($model)
    {
        $results = $model->getMedia($this->id);

        if ($this->maxFiles == 1) {
            return $results->first();
        }

        return $results;
    }
}
