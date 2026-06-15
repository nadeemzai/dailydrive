<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AiProviderController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SourceController as AdminSourceController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// ── Public routes ─────────────────────────────────────────────────────────
Route::get('/', [ArticleController::class, 'index'])->name('home');
Route::get('/articles', [ArticleController::class, 'listing'])->name('articles.index');
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/about',          fn() => view('about'))->name('about');
Route::get('/privacy-policy', fn() => view('privacy-policy'))->name('privacy');
Route::get('/terms',          fn() => view('terms'))->name('terms');
Route::get('/sitemap.xml',    [SitemapController::class, 'index'])->name('sitemap');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// ── Admin auth (no middleware) ────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// ── Admin panel (protected) ───────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('articles', AdminArticleController::class)->except(['show']);
    Route::post('articles/{article}/regenerate', [AdminArticleController::class, 'regenerate'])->name('articles.regenerate');
    Route::post('articles/{article}/publish',    [AdminArticleController::class, 'togglePublish'])->name('articles.publish');

    Route::resource('ai-providers', AiProviderController::class)->except(['show']);
    Route::resource('sources', AdminSourceController::class)->except(['show']);

    Route::get('subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::delete('subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');
});
