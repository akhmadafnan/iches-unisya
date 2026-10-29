<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocaleController
{
    public function __invoke(Request $request)
    {
        $input = $request->validate(['locale' => ['required', 'string', Rule::in(SetLocale::SUPPORTED)]]);
        $request->session()->put('locale', $input['locale']);

        // Fixed destinations also prevent a supplied redirect or Referer becoming an open redirect.
        return redirect($request->user() ? '/dashboard' : '/login');
    }
}
