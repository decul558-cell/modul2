<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\WilayahController;

// =====================
// AUTH
// =====================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/verifikasi-otp', function () {
    return view('auth.otp');
})->name('otp.form');
Route::post('/verifikasi-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');

// =====================
// PROTECTED ROUTES
// =====================
Route::middleware('auth')->group(function () {
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/profile', fn() => view('profile'))->name('profile');

    // Barang
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::post('/barang/cetak-pdf', [BarangController::class, 'cetakPdf'])->name('barang.cetakPdf');
    Route::post('/barang/cetak-pdf-barcode', [BarangController::class, 'cetakPdfBarcode'])->name('barang.cetakPdfBarcode');

    // Kategori
    Route::resource('kategori', KategoriController::class)->except('show');

    // Buku
    Route::resource('buku', BukuController::class)->except('show');

    // POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/bayar', [PosController::class, 'bayar'])->name('pos.bayar');
    Route::post('/pos/cari-barang', [PosController::class, 'cariBarang'])->name('pos.cari');
    Route::get('/pos/riwayat', [PosController::class, 'riwayat'])->name('pos.riwayat');

    // QR Code Generator
    Route::get('/qrcode/{order_code}', function ($order_code) {
        return response(
            \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($order_code)
        )->header('Content-Type', 'image/svg+xml');
    })->name('qrcode.generate');

    // PDF
    Route::get('/pdf/sertifikat', [PdfController::class, 'sertifikat'])->name('pdf.sertifikat');
    Route::get('/pdf/undangan', [PdfController::class, 'undangan'])->name('pdf.undangan');

    // JS Demo Pages
    Route::view('/js-select', 'js.select')->name('js.select');
    Route::view('/js-tabel-biasa', 'js.tabel_biasa')->name('js.tabel_biasa');
    Route::view('/js-tabel-datatables', 'js.tabel_datatables')->name('js.tabel_datatables');
    Route::view('/js-wilayah-ajax', 'js.wilayah_ajax')->name('js.wilayah_ajax');
    Route::view('/js-wilayah-axios', 'js.wilayah_axios')->name('js.wilayah_axios');

    // Wilayah API
    Route::get('/api/provinsi', [WilayahController::class, 'provinsi'])->name('api.provinsi');
    Route::get('/api/kota/{id}', [WilayahController::class, 'kota'])->name('api.kota');
    Route::get('/api/kecamatan/{id}', [WilayahController::class, 'kecamatan'])->name('api.kecamatan');
    Route::get('/api/kelurahan/{id}', [WilayahController::class, 'kelurahan'])->name('api.kelurahan');
});

// =====================
// MIDTRANS WEBHOOK (tanpa auth & tanpa CSRF)
// =====================
Route::post('/midtrans/webhook', [PosController::class, 'webhook'])->name('midtrans.webhook');