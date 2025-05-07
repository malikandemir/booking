<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ResourceRoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    // Language Routes
    Route::get('language/{lang?}', [LanguageController::class, 'switchLang'])->name('language.switch');

    // Test translation route
    Route::get('/test-translation', function() {
        return [
            'current_locale' => app()->getLocale(),
            'translation_test' => __('Efficient Resource Booking System'),
            'all_translations' => trans()->get('*'),
            'session_locale' => session('locale')
        ];
    });

    // Welcome page for unlogged users
    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

    // Privacy Policy page
    Route::view('/privacy-policy', 'privacy-policy')->name('privacy.policy');

    // Terms of Service page
    Route::view('/terms-of-service', 'terms-of-service')->name('terms.service');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Bookings
    Route::resource('bookings', BookingController::class);
    Route::put('/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::put('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::get('/bookings/item/{item}/bookings', [BookingController::class, 'getItemBookings'])->name('bookings.item.bookings');

    Route::resource('items', ItemController::class);
    Route::get('/items/{item}/roles', [ResourceRoleController::class, 'index'])->name('items.roles');
    Route::put('/items/{item}/roles', [ResourceRoleController::class, 'update'])->name('items.roles.update');

    Route::resource('companies', CompanyController::class);

    Route::resource('users', UserController::class);

});
