<?php

namespace AwStudio\Fjord\Actions;

use Illuminate\Http\Request;

class SetLocale
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        $user->update([
            'locale' => $request->locale
        ]);

        return response()->json('success', 200);
    }
}
