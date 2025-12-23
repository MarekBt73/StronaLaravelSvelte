<?php

declare(strict_types=1);

use App\Http\Controllers\BlogController;
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

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/kategoria/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

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
