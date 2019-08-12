<?php

namespace AwStudio\Fjord\Fjord;

use AwStudio\Fjord\Models\Repeatable;
use AwStudio\Fjord\Models\PageContent;

class Fjord
{
    use Concerns\ManagesNavigation,
        Concerns\ManagesPages,
        Concerns\ManagesCruds;

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

    public static function page($name)
    {
        $repeatables = Repeatable::with('media', 'translations')->orderByRaw('-order_column DESC')->where('page_name', $name)->get();
        $groupedRepeatables = $repeatables->groupBy('block_name');

        return [
            'content' => PageContent::with('translations')->where('page_name', $name)->get(),
            'repeatables' => $groupedRepeatables
        ];
    }
}
