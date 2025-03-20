<?php

use App\Livewire\Admin\DataPeminjaman;
use App\Livewire\Admin\DataUser;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\DataBuku;
use App\Livewire\Home\Index;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Books\Index as BooksIndex;
use App\Livewire\Books\Detail;
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

Route::get('/buku', BooksIndex::class)->name('buku');
Route::get('/buku/{slug}', Detail::class)->name('buku.detail');

// Auth Routes - only for guests
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Admin Routes
Route::middleware(['auth', 'role:ADMIN'])->group(function () {
    Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('/admin/data-user', DataUser::class)->name('admin.data-user');
    Route::get('/admin/data-buku', DataBuku::class)->name('admin.data-buku');
    Route::get('/admin/data-peminjaman', DataPeminjaman::class)->name('admin.data-peminjaman');
});

// Protected Routes dengan pengecekan status peminjaman
Route::middleware(['auth', 'check.peminjaman'])->group(function () {
    Route::get('/peminjaman/create/{token}', \App\Livewire\Books\CreatePeminjaman::class)->name('peminjaman.create');
});

// Protected Routes tanpa pengecekan status peminjaman
Route::middleware(['auth'])->group(function () {
    Route::get('/peminjaman/{id}', \App\Livewire\Books\DetailPeminjaman::class)->name('peminjaman.detail');
    Route::get('/peminjaman', \App\Livewire\Books\Peminjaman::class)->name('peminjaman');
    Route::get('/profile', \App\Livewire\User\Profile::class)->name('profile');
    Route::get('/favorit', App\Livewire\Books\Favorite::class)->name('favorit');
    Route::get('/bookmark', App\Livewire\Books\Bookmark::class)->name('bookmark');
});