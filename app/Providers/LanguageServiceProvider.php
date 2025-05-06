<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Lang;

class LanguageServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Lang::addJsonPath(base_path('lang'));
        
        $this->loadTranslationsFrom(base_path('lang'), 'messages');
        $this->loadJsonTranslationsFrom(base_path('lang'));
    }
}
