<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TrackRecordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
| The public website — React pages rendered through Inertia.
*/
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/about', [SiteController::class, 'about'])->name('about');
Route::get('/services', [SiteController::class, 'services'])->name('services');
Route::get('/projects', [SiteController::class, 'projects'])->name('projects');
Route::get('/credentials', [SiteController::class, 'credentials'])->name('credentials');
Route::get('/gallery', [SiteController::class, 'gallery'])->name('gallery');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'storeMessage'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('projects', ProjectController::class)->except('show');
    Route::resource('track-records', TrackRecordController::class)->except('show');
    Route::resource('gallery', GalleryController::class)->except('show')->parameters(['gallery' => 'image']);
    Route::resource('services', ServiceController::class)->except('show');
    Route::resource('testimonials', TestimonialController::class)->except('show');
    Route::resource('faqs', FaqController::class)->except('show');
    Route::resource('posts', PostController::class)->except('show');

    Route::get('content/{group?}', [ContentController::class, 'edit'])->name('content.edit');
    Route::put('content/{group}', [ContentController::class, 'update'])->name('content.update');

    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
});

Route::fallback(fn () => Inertia::render('NotFound')->toResponse(request())->setStatusCode(404));
