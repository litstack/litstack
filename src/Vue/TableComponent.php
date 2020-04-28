<?php

namespace Fjord\Vue;



class TableComponent extends Component
{
    use Concerns\IsTableCol;

    /**
     * Get array.
     *
     * @return array
     */
    public function getArray(): array
    {
        $array = parent::getArray();
        unset($array['name']);
        $array['component'] = $this->name;
        return array_merge(
            $this->attributes,
            $array
        );
    }

    /**
     * Get missing props and attributes
     *
     * @return array
     */
    protected function getMissing()
    {
        $missing = [];
        foreach (array_merge($this->required, $this->required()) as $prop) {
            if (array_key_exists($prop, $this->props) || array_key_exists($prop, $this->attributes)) {
                continue;
            }

            $missing[] = $prop;
        }

        return $missing;
    }

    /**
     * Set defaults.
     *
     * @return void
     */
    protected function setDefault()
    {
        parent::setDefaults();
    }
}
