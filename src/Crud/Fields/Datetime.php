<?php

namespace Fjord\Crud\Fields;

use Carbon\CarbonInterface;
use Fjord\Crud\BaseField;
use Fjord\Support\Facades\FjordApp;
use Illuminate\Support\Carbon;

class Datetime extends BaseField
{
    use Traits\FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-date-time';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Set default attributes.
     *
     * @return void
     */
    public function mount()
    {
        $this->includeCtkScript();

        $this->formatted('l');
        $this->inline(false);
        $this->onlyDate(true);
    }

    /**
     * Inlcude ctk datetime picker script.
     *
     * @see https://github.com/chronotruck/vue-ctk-date-time-picker
     *
     * @return void
     */
    protected function includeCtkScript()
    {
        FjordApp::script(fjord()->route('ctk.js'));
    }

    /**
     * Set formatted.
     *
     * @param  string $format
     * @return $this
     */
    public function formatted(string $format)
    {
        $this->setAttribute('formatted', $format);

        return $this;
    }

    /**
     * Set inline.
     *
     * @param  bool  $inline
     * @return $this
     */
    public function inline(bool $inline = true)
    {
        $this->setAttribute('inline', $inline);

        return $this;
    }

    /**
     * Set only date.
     *
     * @param  bool  $date
     * @return $this
     */
    public function onlyDate(bool $dateOnly = true)
    {
        $this->setAttribute('only_date', $dateOnly);

        if (! $dateOnly) {
            $this->formatted('llll');
        } else {
            $this->formatted('l');
        }

        return $this;
    }

    /**
     * Cast field value.
     *
     * @param  mixed $value
     * @return bool
     */
    public function castValue($value)
    {
        if ($value instanceof CarbonInterface) {
            return $value;
        }

        return new Carbon($value);
    }
}
