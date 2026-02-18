<?php

namespace App\Http\Controllers;

use App\Models\CmsContent;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        // Determine locale from session, cookie, or browser
        $locale = $request->session()->get(
            'locale',
            $request->cookie('locale', 'en')
        );

        if (!in_array($locale, ['en', 'id'])) {
            $locale = 'en';
        }

        $cms = CmsContent::forLocale($locale);

        return view('landing', compact('cms', 'locale'));
    }

    public function setLocale(Request $request, string $locale)
    {
        if (!in_array($locale, ['en', 'id'])) {
            $locale = 'en';
        }

        $request->session()->put('locale', $locale);

        return back()->withCookie(cookie()->forever('locale', $locale));
    }
}
