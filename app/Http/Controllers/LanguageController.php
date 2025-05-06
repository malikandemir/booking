<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    public function switchLang(Request $request, $lang = null)
    {
        // Get language from route parameter or request
        $locale = $lang ?? $request->lang ?? 'tr';
        
        // Validate language
        if (!in_array($locale, ['en', 'tr'])) {
            $locale = 'tr';
        }

        // Clear old session data
        Session::forget('locale');
        
        // Set session and app locale
        Session::put('locale', $locale);
        App::setLocale($locale);

        // Force the session to be saved immediately
        Session::save();

        // Log the language change
        Log::info('Language switched', [
            'new_locale' => $locale,
            'session_locale' => Session::get('locale'),
            'app_locale' => App::getLocale(),
            'from_url' => $request->fullUrl(),
            'referer' => $request->header('referer')
        ]);

        // Redirect back with a flash message
        return redirect()->back()->with('locale_switched', true);
    }
}
