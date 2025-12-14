<?php 

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaLinkController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;

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
Route::get('/about', function () {
    return view('pricing.about');
})->name('about');

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
// 8️⃣ ADMIN ROUTES (NEW - Add before catch-all slug)
// ===================================================
// ===================================================
// 8️⃣ ADMIN ROUTES (NEW - Add before catch-all slug)
// ===================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    
    // Plans Management
    Route::get('/plans', [AdminController::class, 'plans'])->name('plans');
    Route::post('/create-plan', [AdminController::class, 'createPlan'])->name('create-plan');
    Route::post('/toggle-plan/{plan}', [AdminController::class, 'togglePlanStatus'])->name('toggle-plan');
    Route::delete('/plans/{plan}/delete', [AdminController::class, 'deletePlan'])->name('delete-plan'); // Add this line
    
    // Subscriptions Management
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
    
    // User Subscription Actions
    Route::post('/users/{user}/add-links', [AdminController::class, 'addLinks'])->name('add-links');
    Route::post('/users/{user}/extend-plan', [AdminController::class, 'extendUserPlan'])->name('extend-plan');
    Route::post('/users/{user}/upgrade-plan', [AdminController::class, 'upgradePlan'])->name('upgrade-plan');
    
    // Assign Plan Routes
    Route::get('/assign-plan/{userId?}', [AdminController::class, 'assignPlanForm'])->name('assign-plan.form');
    Route::post('/assign-plan', [AdminController::class, 'assignPlan'])->name('assign-plan');
    
    // Cancel Subscription
    Route::post('/cancel-subscription/{user}', [AdminController::class, 'cancelSubscription'])->name('cancel-subscription');
});
// Home route redirect to admin dashboard if admin
Route::get('/home', function () {
    if (auth()->check() && auth()->user()->email === 'ak3400988@gmail.com') {
        return redirect()->route('admin.dashboard');
    }
    return redirect('/');
});

// ===================================================
// 7️⃣ CATCH-ALL SLUG (ALWAYS LAST!)
// ===================================================
Route::get('/{slug}', [WaLinkController::class, 'redirect'])
    ->where('slug', '[A-Za-z0-9\-]+')
    ->name('wa-links.redirect');