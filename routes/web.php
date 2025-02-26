<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Home\Index;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home route - accessible for both guest and authenticated users
Route::get('/', Index::class)->name('home');

// Auth Routes - only for guests
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Protected Routes - authenticated users only
Route::middleware('auth')->group(function () {
    // Fitur-fitur lain yang membutuhkan autentikasi
    Route::get('/dashboard', function() {
        return view('dashboard');
    })->name('dashboard');
    
    // Tambahkan fitur lain yang membutuhkan autentikasi di sini
});

// Admin Routes
Route::middleware(['auth', 'role:ADMIN'])->group(function () {
    Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');
});