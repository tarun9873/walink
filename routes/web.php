<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WaLinkController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CallLinkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;

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


Route::get('/privacy-policy', function () {
    return view('pricing.privacy');
})->name('privacy-policy');

Route::get('/link-not-found', [WaLinkController::class, 'notfound'])->name('wa-links.notfound');

// ===================================================
// 3️⃣ USER DASHBOARD (Auth required)
// ===================================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

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
Route::middleware(['auth', 'verified'])->group(function () {
    // Index, show, edit, update, destroy - basic auth only
    Route::get('/wa-links', [WaLinkController::class, 'index'])->name('wa-links.index');
    Route::get('/wa-links/{waLink}/edit', [WaLinkController::class, 'edit'])->name('wa-links.edit');
    Route::put('/wa-links/{waLink}', [WaLinkController::class, 'update'])->name('wa-links.update');
    Route::delete('/wa-links/{waLink}', [WaLinkController::class, 'destroy'])->name('wa-links.destroy');
    
    // Analytics
    Route::get('/wa-links/{id}/analytics', [WaLinkController::class, 'analytics'])->name('wa-links.analytics');
    
    // Create and Store with subscription and link limit check
    Route::middleware(['subscription', 'link.limit'])->group(function () {
        Route::get('/wa-links/create', [WaLinkController::class, 'create'])->name('wa-links.create');
        Route::post('/wa-links', [WaLinkController::class, 'store'])->name('wa-links.store');
    });
});

// ===================================================
// 7️⃣ ADMIN ROUTES
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
    Route::delete('/plans/{plan}/delete', [AdminController::class, 'deletePlan'])->name('delete-plan');
    
    // Subscriptions Management
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
    // View User Details
    Route::get('/users/{id}', [AdminController::class, 'viewUser'])->name('view-user');
    
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
  // ADD CALL LINKS HERE FOR ADMIN
 // ADD CALL LINKS HERE FOR ADMIN


// Admin Call Links Routes (with admin prefix)
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/call-links', [CallLinkController::class, 'index'])->name('admin.call-links.index');
    Route::get('/call-links/create', [CallLinkController::class, 'create'])->name('admin.call-links.create');
    Route::post('/call-links', [CallLinkController::class, 'store'])->name('admin.call-links.store');
    Route::get('/call-links/{callLink}/edit', [CallLinkController::class, 'edit'])->name('admin.call-links.edit');
    Route::put('/call-links/{callLink}', [CallLinkController::class, 'update'])->name('admin.call-links.update');
    Route::delete('/call-links/{callLink}', [CallLinkController::class, 'destroy'])->name('admin.call-links.destroy');
    Route::get('/call-links/{callLink}/analytics', [CallLinkController::class, 'analytics'])->name('admin.call-links.analytics');
});

// Public routes (no auth required)
Route::get('/call/{slug}', [CallLinkController::class, 'redirect'])->name('call.redirect');
Route::get('/call-links/notfound', [CallLinkController::class, 'notfound'])->name('call-links.notfound');



// Home route redirect to admin dashboard if admin
Route::get('/home', function () {
    if (auth()->check() && auth()->user()->email === 'ak3400988@gmail.com') {
        return redirect()->route('admin.dashboard');
    }
    return redirect('/');
});

// ===================================================
// 8️⃣ CATCH-ALL SLUG (ALWAYS LAST!)
// ===================================================
Route::get('/{slug}', [WaLinkController::class, 'redirect'])
    ->where('slug', '[A-Za-z0-9\-]+')
    ->name('wa-links.redirect');

   