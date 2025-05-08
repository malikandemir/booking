<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResourceController;
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
    Route::resource('roles', \App\Http\Controllers\RoleController::class)->except(['show']);
    Route::resource('permissions', \App\Http\Controllers\PermissionController::class)->except(['show']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Bookings
    Route::resource('bookings', BookingController::class);
    Route::put('/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::put('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::get('/bookings/resource/{resource}/bookings', [BookingController::class, 'getResourceBookings'])->name('bookings.resource.bookings');

    Route::resource('resources', ResourceController::class);
    Route::get('/resources/{resource}/roles', [ResourceRoleController::class, 'index'])->name('resources.roles');
    Route::put('/resources/{resource}/roles', [ResourceRoleController::class, 'update'])->name('resources.roles.update');

    Route::resource('companies', CompanyController::class);

    Route::resource('users', UserController::class);

});
