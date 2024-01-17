<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// Routes for authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Registration routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Routes for authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/calendar', fn() => view('calendar'))->name('calendar.index');
    Route::get('/navbar', [HomeController::class, 'navbar']);

    // Add more authenticated routes as needed

    // Logout route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Default welcome route
Route::get('/', fn() => view('welcome'));

// Event routes
//Route::post('/event', [EventController::class, 'store']);
Route::get('/events', [EventController::class, 'getEvents']);
Route::put('/events/{event}', [EventController::class, 'update']);
Route::delete('/events/{event}', [EventController::class, 'destroy']);
Route::post('/events', 'EventController@store');



// Uncomment the line below if you want to include additional routes for events
// Route::resource('events', 'EventController');
