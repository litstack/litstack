<?php

namespace Fjord\Crud\Fields\Relations;

use Fjord\Crud\Fields\Relations\ManyRelation;
use Fjord\Exceptions\InvalidArgumentException;

class ManyRelationField extends LaravelRelationField
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-relation';

    /**
     * Available field attributes.
     *
     * @var array
     */
    protected $availableManyRelationAttributes = [
        'sortable',
        'tagVariant',
        'tags',
        'showTableHead',
        'searchable',
        'perPage',
    ];


    /**
     * Set default field attributes.
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        parent::setDefaultAttributes();

        $this->setAttribute('many', true);
        $this->setAttribute('searchable', false);
        $this->setAttribute('perPage', 10);
        $this->setAttribute('sortable', false);
        $this->setAttribute('tagVariant', 'secondary');
    }

    /**
     * Set showTableHead
     *
     * @param boolean $show
     * @return $this
     */
    public function showTableHead(bool $show = true)
    {
        $this->setAttribute('showTableHead', $bool);

        return $this;
    }

    /**
     * Set perPage.
     *
     * @param integer $perPage
     * @return $this
     */
    public function perPage(int $perPage)
    {
        $this->setAttribute('perPage', $perPage);

        return $this;
    }

    /**
     * Set tag view.
     *
     * @param string $value
     * @return $this
     */
    public function tagVariant(string $value)
    {
        $this->setAttribute('tags', $value);

        return $this;
    }

    /**
     * Set tag view.
     *
     * @param string $value
     * @return $this
     */
    public function tags(string $value)
    {
        $this->setAttribute('tags', $value);

        return $this;
    }

    /**
     * Set searchable.
     *
     * @param boolean $searchable
     * @return $this
     */
    public function searchable(bool $searchable = true)
    {
        $this->setAttribute('searchable', $searchable);

        return $this;
    }

    /**
     * Set sortable field.
     *
     * @return self
     */
    public function sortable($sort = true)
    {
        if (!$this->getRelatedModelClass()) {
            throw new InvalidArgumentException('You may set a related Model before making the Field sortable.', [
                'function' => 'sortable',
                'class' => 'Fjord\Crud\ManyRelationField'
            ]);
        }

        if (empty($this->getRelationQuery(new $this->model)->getQuery()->getQuery()->orders)) {
            throw new InvalidArgumentException('You may add orderBy to the related query for ' . $this->id . ' in ' . $this->model . '.', [
                'function' => 'sortable',
                'class' => 'Fjord\Crud\ManyRelationField'
            ]);
        }

        $this->attributes['sortable'] = $sort;

        return $this;
    }
}
