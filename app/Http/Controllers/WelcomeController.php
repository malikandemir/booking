<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class WelcomeController extends Controller
{
    public function index()
    {
        // Set the application locale based on session
        $locale = Session::get('locale', config('app.locale', 'tr'));
        App::setLocale($locale);
        
        // Debug information
        Log::info('Welcome page loaded', [
            'session_locale' => Session::get('locale'),
            'app_locale' => App::getLocale(),
            'config_locale' => config('app.locale'),
            'translation_test' => __('Efficient Resource Booking System'),
            'has_translation' => trans()->has('Efficient Resource Booking System')
        ]);

        return view('welcome', [
            'debug' => [
                'locale' => App::getLocale(),
                'translation_test' => __('Efficient Resource Booking System')
            ]
        ]);
    }
}
