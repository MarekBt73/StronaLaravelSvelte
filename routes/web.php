<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Health check endpoint for Railway (no database required)
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]);
})->name('health');

// Test email endpoint (remove in production after testing!)
Route::get('/test-email/{email}', function (string $email) {
    try {
        \Illuminate\Support\Facades\Mail::raw(
            "Test email z MedVita.\n\nData: " . now()->format('Y-m-d H:i:s'),
            fn($message) => $message->to($email)->subject('Test MedVita')
        );
        return response()->json(['status' => 'sent', 'to' => $email]);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
})->name('test.email');

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/o-nas', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/uslugi', function () {
    return Inertia::render('Services');
})->name('services');

Route::get('/lekarze', function () {
    return Inertia::render('Doctors');
})->name('doctors');

Route::get('/kontakt', function () {
    return Inertia::render('Contact');
})->name('contact');

// Legal pages
Route::get('/regulamin', function () {
    return Inertia::render('Legal/Terms');
})->name('legal.terms');

Route::get('/polityka-prywatnosci', function () {
    return Inertia::render('Legal/Privacy');
})->name('legal.privacy');

Route::get('/rodo', function () {
    return Inertia::render('Legal/GDPR');
})->name('legal.gdpr');

// Placeholder for booking
Route::get('/rezerwacja', function () {
    return Inertia::render('Home'); // TODO: Booking page
})->name('booking');
