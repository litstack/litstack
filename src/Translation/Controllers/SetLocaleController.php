<?php

namespace Fjord\Translation\Controllers;

use Illuminate\Http\Request;

class SetLocaleController
{
    /**
     * Set locale.
     *
     * @param  Request  $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        if (! in_array($request->locale, config('fjord.translatable.locales'))) {
            abort(404, "Locale [{$request->locale}] is not available.");
        }

        fjord_user()->update([
            'locale' => $request->locale,
        ]);
    }
}
