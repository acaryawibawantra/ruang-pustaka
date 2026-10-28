<?php

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\LoanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'landing'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('login.store');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('register.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', MemberDashboardController::class)->name('dashboard');
});

Route::middleware(['auth', 'active'])->prefix('pinjaman')->name('member.loans.')->group(function () {
    Route::get('/', [LoanController::class, 'index'])->name('index');
    Route::get('/{loan}', [LoanController::class, 'show'])->name('show');
});

Route::view('/design-system', 'design-system')->name('design-system');

Route::middleware(['auth', 'active', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('buku', BookController::class)->parameters(['buku' => 'book'])->names('books')->except(['show', 'destroy']);
    Route::patch('buku/{book}/nonaktifkan', [BookController::class, 'deactivate'])->name('books.deactivate');
    Route::get('peminjaman', [AdminLoanController::class, 'index'])->name('loans.index');
    Route::get('peminjaman/buat', [AdminLoanController::class, 'create'])->name('loans.create');
    Route::post('peminjaman', [AdminLoanController::class, 'store'])->name('loans.store');
    Route::get('peminjaman/{loan}/kembali', [AdminLoanController::class, 'edit'])->name('loans.edit');
    Route::put('peminjaman/{loan}/kembali', [AdminLoanController::class, 'update'])->name('loans.update');
    Route::resource('kategori', CategoryController::class)->parameters(['kategori' => 'category'])->names('categories')->except(['show']);
    Route::get('anggota', [MemberController::class, 'index'])->name('members.index');
    Route::get('anggota/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::get('anggota/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('anggota/{member}', [MemberController::class, 'update'])->name('members.update');
    Route::patch('anggota/{member}/status', [MemberController::class, 'toggleStatus'])->name('members.toggle-status');
});

Route::get('/design-system/admin', function () {
    abort_unless(app()->environment(['local', 'testing']), 404);

    return view('admin.dashboard', ['preview' => true, 'metrics' => null, 'overdueLoans' => collect()]);
})->name('design-system.admin');

Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{book}', [CatalogController::class, 'show'])->name('catalog.show');
