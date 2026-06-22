<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ReviewController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminTaskController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminReviewController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Stripe Webhook
|--------------------------------------------------------------------------
*/

Route::post('/stripe/webhook', [SubscriptionController::class, 'webhook'])
    ->name('stripe.webhook');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Laravel Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Tasks
    |--------------------------------------------------------------------------
    */
    Route::resource('tasks', TaskController::class);

    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])
        ->name('tasks.status');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Subscription
    |--------------------------------------------------------------------------
    */
    Route::get('/subscription', [SubscriptionController::class, 'index'])
        ->name('subscription.index');

    Route::post('/subscription/checkout/{subscription}', [SubscriptionController::class, 'checkout'])
        ->name('subscription.checkout');

    Route::get('/subscription/success', [SubscriptionController::class, 'success'])
        ->name('subscription.success');

    /*
    |--------------------------------------------------------------------------
    | Payment History
    |--------------------------------------------------------------------------
    */
    Route::get('/payments', [SubscriptionController::class, 'history'])
        ->name('payment.history');

    /*
    |--------------------------------------------------------------------------
    | Reviews
    |--------------------------------------------------------------------------
    */
    Route::resource('reviews', ReviewController::class)
        ->only(['index', 'create', 'store', 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\AdminOnly::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users');

        Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])
            ->name('users.role');

        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
            ->name('users.destroy');

        /*
        |--------------------------------------------------------------------------
        | Tasks
        |--------------------------------------------------------------------------
        */
        Route::get('/tasks', [AdminTaskController::class, 'index'])
            ->name('tasks');

        /*
        |--------------------------------------------------------------------------
        | Payments
        |--------------------------------------------------------------------------
        */
        Route::get('/payments', [AdminPaymentController::class, 'index'])
            ->name('payments');

        /*
        |--------------------------------------------------------------------------
        | Reviews
        |--------------------------------------------------------------------------
        */
        Route::get('/reviews', [AdminReviewController::class, 'index'])
            ->name('reviews');

        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])
            ->name('reviews.destroy');
    });