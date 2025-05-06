<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session or use the default from config
        $locale = Session::get('locale', config('app.locale', 'tr'));
        
        // Validate language
        if (!in_array($locale, ['en', 'tr'])) {
            $locale = 'tr';
        }

        // Set the application locale
        App::setLocale($locale);
        
        // Log for debugging
        Log::info('Language middleware', [
            'session_locale' => Session::get('locale'),
            'config_locale' => config('app.locale'),
            'app_locale' => App::getLocale(),
            'request_path' => $request->path()
        ]);

        return $next($request);
    }
}
