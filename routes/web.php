<?php

declare(strict_types=1);

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AssetController::class, 'index'])->name('asset.index');
// Redirect for compatibility with the old asset library homepage URL
Route::permanentRedirect('/asset', url('/'));
Route::get('/asset/submit', [AssetController::class, 'create'])->name('asset.create')->middleware('can:submit-asset');
Route::post('/asset', [AssetController::class, 'store'])->name('asset.store')->middleware('can:submit-asset');

Route::get('/asset/{asset}', [AssetController::class, 'show'])->name('asset.show')->middleware('can:view-asset,asset');
Route::get('/asset/{asset}/edit', [AssetController::class, 'edit'])->name('asset.edit')->middleware('can:edit-asset,asset');
Route::put('/asset/{asset}', [AssetController::class, 'update'])->name('asset.update')->middleware('can:edit-asset,asset');
Route::put('/asset/{asset}/archive', [AssetController::class, 'archive'])->name('asset.archive')->middleware('can:edit-asset,asset');
Route::put('/asset/{asset}/unarchive', [AssetController::class, 'unarchive'])->name('asset.unarchive')->middleware('can:edit-asset,asset');
Route::put('/asset/{asset}/publish', [AssetController::class, 'publish'])->name('asset.publish')->middleware('can:admin');
Route::put('/asset/{asset}/unpublish', [AssetController::class, 'unpublish'])->name('asset.unpublish')->middleware('can:admin');

Route::post('/asset/{asset}/reviews', [AssetController::class, 'storeReview'])->name('asset.reviews.store')->middleware('can:submit-review,asset');
Route::post('/asset/reviews/{asset_review}', [AssetController::class, 'storeReviewReply'])->name('asset.reviews.replies.store')->middleware('can:submit-review-reply,asset_review');
Route::put('/asset/reviews/{asset_review}', [AssetController::class, 'updateReview'])->name('asset.reviews.update')->middleware('can:edit-review,asset_review');
Route::delete('/asset/reviews/{asset_review}', [AssetController::class, 'destroyReview'])->name('asset.reviews.destroy')->middleware('can:edit-review,asset_review');

Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
Route::get('/user/{user}/reviews', [UserController::class, 'indexReviews'])->name('user.reviews.index');

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index')->middleware('can:admin');
Route::put('/admin/users/{user}/block', [AdminController::class, 'block'])->name('admin.block')->middleware('can:admin');
Route::put('/admin/users/{user}/unblock', [AdminController::class, 'unblock'])->name('admin.unblock')->middleware('can:admin');

// Register authentication-related routes (including email verification routes)
Auth::Routes(['verify' => true]);

// OAuth2 authentication routes
Route::get('login/{provider}', [LoginController::class, 'redirectToProvider'])->name('login.oauth2');
Route::get('login/{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name('login.oauth2.callback');
