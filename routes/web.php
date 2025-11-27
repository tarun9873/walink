<?php 

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaLinkController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===================================================
// 1️⃣ AUTH ROUTES (Must be FIRST, before slug route)
// ===================================================
require __DIR__.'/auth.php';

// ===================================================
// 2️⃣ PUBLIC ROUTES
// ===================================================
Route::get('/', [PricingController::class, 'index'])->name('pricing');

Route::get('/link-not-found', function () {
    return view('wa_links.notfound');
})->name('wa-links.notfound');

// ===================================================
// 3️⃣ USER DASHBOARD (Auth required)
// ===================================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// ===================================================
// 4️⃣ PROFILE ROUTES
// ===================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===================================================
// 5️⃣ SUBSCRIPTIONS
// ===================================================
Route::middleware('auth')->group(function () {
    Route::post('/subscribe/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
});




// ===================================================
// 6️⃣ WA-LINKS ROUTES (Protected)
// ===================================================
Route::middleware(['auth', 'verified', 'subscription'])->group(function () {

    Route::get('/wa-links', [WaLinkController::class, 'index'])->name('wa-links.index');
    Route::get('/wa-links/{waLink}/edit', [WaLinkController::class, 'edit'])->name('wa-links.edit');
    Route::put('/wa-links/{waLink}', [WaLinkController::class, 'update'])->name('wa-links.update');
    Route::delete('/wa-links/{waLink}', [WaLinkController::class, 'destroy'])->name('wa-links.destroy');

    // Create/Store with link limit
    Route::middleware('link.limit')->group(function () {
        Route::get('/wa-links/create', [WaLinkController::class, 'create'])->name('wa-links.create');
        Route::post('/wa-links', [WaLinkController::class, 'store'])->name('wa-links.store');
    });
});


Route::get('/wa-links/{id}/analytics', [WaLinkController::class, 'analytics'])->name('wa-links.analytics');
Route::resource('wa-links', WaLinkController::class);


// ===================================================
// 7️⃣ CATCH-ALL SLUG (ALWAYS LAST!)
// ===================================================
Route::get('/{slug}', [WaLinkController::class, 'redirect'])
    ->where('slug', '[A-Za-z0-9\-]+')
    ->name('wa-links.redirect');