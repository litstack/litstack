<?php

namespace Ignite\Translation\Controllers;

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
        if (! in_array($request->locale, config('lit.translatable.locales'))) {
            abort(404, "Locale [{$request->locale}] is not available.");
        }

        lit_user()->update([
            'locale' => $request->locale,
        ]);
    }
}
