<?php

namespace AwStudio\Fjord\Fjord;

use AwStudio\Fjord\Models\Repeatable;
use AwStudio\Fjord\Models\PageContent;

class Fjord
{
    use Concerns\ManagesNavigation,
        Concerns\ManagesForms;

    public function __construct()
    {

    }

    protected function prepareFields($fields, $path, $setDefaults = null)
    {
        foreach($fields as $key => $field) {
            $fields[$key] = new FormFields\FormField($field, $path, $setDefaults);
        }
        return form_collect($fields);
    }

    public function routes()
    {
        require fjord_path('routes/fjord.php');
    }
}
