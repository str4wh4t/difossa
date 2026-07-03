<?php

use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\DownloadCompetitionRegistrationArticleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/competitions', [CompetitionController::class, 'index'])->name('competitions.index');
Route::get('/competitions/{competition:slug}', [CompetitionController::class, 'show'])->name('competitions.show');
Route::get('/blog', [PostController::class, 'index'])->name('posts.index');
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::middleware('auth')->get(
    '/admin/competition-registrations/{registration}/article/download',
    DownloadCompetitionRegistrationArticleController::class,
)->name('competition-registrations.article.download');
Route::get('/{page:slug}', [PageController::class, 'show'])
    ->name('pages.show')
    ->where('page', '^(?!admin|blog|competitions|livewire|storage|build).*$');
