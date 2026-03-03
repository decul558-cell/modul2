<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BarangController;

Auth::routes(['register' => false]);

// ============================================
// GOOGLE LOGIN
// ============================================
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])
    ->name('google.login');

Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])
    ->name('google.callback');

// ============================================
// OTP
// ============================================
Route::get('/verifikasi-otp', function () {
    return view('auth.otp');
})->name('otp.form');

Route::post('/verifikasi-otp', [AuthController::class, 'verifyOtp'])
    ->name('otp.verify');

// ============================================
// ROOT
// ============================================
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ══════════════════════════════════════════
// SEMUA USER LOGIN
// ══════════════════════════════════════════
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [HomeController::class, 'index'])
        ->name('dashboard');

    Route::get('/kategori', [KategoriController::class, 'index'])
        ->name('kategori.index');

    Route::get('/buku', [BukuController::class, 'index'])
        ->name('buku.index');

    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');

    // ========================================
    // PDF (Role-aware di controller)
    // ========================================
    Route::get('/pdf/sertifikat', [PdfController::class, 'sertifikat'])
        ->name('pdf.sertifikat');

    Route::get('/pdf/undangan', [PdfController::class, 'undangan'])
        ->name('pdf.undangan');

    // ========================================
    // BARANG (Tag Harga UMKM)
    // ========================================
    Route::post('barang/cetak-pdf', [BarangController::class, 'cetakPdf'])
        ->name('barang.cetakPdf');

    Route::resource('barang', BarangController::class)
        ->except(['show', 'create', 'edit']);
});

// ══════════════════════════════════════════
// ADMIN ONLY
// ══════════════════════════════════════════
Route::middleware(['auth', 'admin'])->group(function () {

    Route::resource('kategori', KategoriController::class)
        ->except(['index', 'show']);

    Route::resource('buku', BukuController::class)
        ->except(['index', 'show']);
});

// ============================================
// FALLBACK
// ============================================
Route::fallback(function () {
    return redirect()->route('dashboard');
});