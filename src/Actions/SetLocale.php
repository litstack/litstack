<?php

namespace AwStudio\Fjord\Actions;

use Illuminate\Http\Request;

class SetLocale
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        
        if(! in_array($request->locale, config('fjord.translatable.locales'))) {
            return response()->json('method not allowed', 405);
        }

        $user->update([
            'locale' => $request->locale
        ]);

        return response()->json('success', 200);
    }
}
