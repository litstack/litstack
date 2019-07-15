<?php

namespace AwStudio\Fjord;

use AwStudio\Fjord\Models\Repeatable;
use AwStudio\Fjord\Models\PageContent;

class Fjord
{
    public function routes()
    {
        require __DIR__.'/../routes/fjord.php';
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
