<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EventController;

// Route to display the login form
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Route to handle login form submission
Route::post('/login', [LoginController::class, 'login']);

// Registration routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Routes for authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/calendar', function () {
        return view('calendar');
    });
    // Add more authenticated routes as needed

    // Navbar route
    Route::get('/navbar', [HomeController::class, 'navbar']);
});

// Default welcome route
Route::get('/', function () {
    return view('welcome');
});

Route::post('/event', [EventController::class, 'store']);

// routes/web.php

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
