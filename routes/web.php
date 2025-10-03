<?php

use App\Http\Controllers\Admin\AccountSettingsController;
use App\Http\Controllers\Admin\AffiliateSettingController;
use App\Http\Controllers\Admin\BrandSettingsController;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VideosController;
use App\Http\Controllers\Admin\WebSettingsController;
use App\Http\Controllers\API\VideoLikeController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/watch/{slug}', [HomeController::class, 'show'])->name('watch');
Route::get('/search', [HomeController::class, 'search'])->name('search');

Route::get('/admin', function () {
  return redirect()->route('admin.dashboard');
});

// Guest Routes (Login, Register, Password Reset)
Route::middleware('guest')->group(function () {
  Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [LoginController::class, 'login']);

  Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
  Route::post('/register', [RegisterController::class, 'Register']);

  Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
  Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

  Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
  Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Logout Route (Authenticated users only)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes - HANYA untuk role ADMIN
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  // Account Settings
  Route::get('/account-settings', [AccountSettingsController::class, 'index'])->name('account.settings');
  Route::put('/account-settings', [AccountSettingsController::class, 'update'])->name('account.update');

  // Web Settings
  Route::get('/web-settings', [WebSettingsController::class, 'index'])->name('web.settings');
  Route::put('/web-settings', [WebSettingsController::class, 'update'])->name('web.update');

  // Brand Settings
  Route::get('/brand-settings', [BrandSettingsController::class, 'index'])->name('brand.settings');
  Route::post('/brand-settings', [BrandSettingsController::class, 'update'])->name('brand.update');
  Route::delete('/brand-settings/logo', [BrandSettingsController::class, 'deleteLogo'])->name('brand.logo.delete');
  Route::delete('/brand-settings/favicon', [BrandSettingsController::class, 'deleteFavicon'])->name('brand.favicon.delete');

  // Categories Management
  Route::resource('categories', CategoriesController::class);
  Route::post('/categories/{category}/toggle', [CategoriesController::class, 'toggleStatus'])->name('categories.toggle');

  // Videos Management
  Route::resource('videos', VideosController::class);
  Route::delete('/videos/{video}/thumbnail', [VideosController::class, 'deleteThumbnail'])->name('videos.thumbnail.delete');


  // Affiliate Settings
  Route::get('/affiliate', [AffiliateSettingController::class, 'index'])->name('affiliate.index');
  Route::put('/affiliate', [AffiliateSettingController::class, 'update'])->name('affiliate.update');

  // Cache Management
  Route::post('/cache/clear', [CacheController::class, 'clear'])->name('cache.clear');
  Route::post('/cache/optimize', [CacheController::class, 'optimize'])->name('cache.optimize');
});

Route::middleware('auth')->group(function () {
  Route::post('/videos/{video}/comments', [CommentController::class, 'store'])->name('comments.store');
  Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
  Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
  Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
});
Route::post('/videos/{slug}/like', [HomeController::class, 'like'])->name('videos.like');


Route::get('/linkstorage', function () {
  Artisan::call('storage:link');
});
