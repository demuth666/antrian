<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        abort_unless(
            in_array($locale, config('app.available_locales', ['id', 'en']), true),
            404,
        );

        $request->session()->put('locale', $locale);

        return redirect()
            ->back(302, [], route('home'))
            ->withCookie(cookie()->forever('locale', $locale));
    }
}
