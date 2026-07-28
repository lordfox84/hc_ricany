<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\PublicArticleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CampController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CampController as AdminCampController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Admin\PlayerController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\SubscriberController as AdminSubscriberController;
use App\Http\Controllers\Admin\PublicSkatingController as AdminSkatingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/
Route::get('/jazyk/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tymy/{team:slug}', [TeamController::class, 'show'])->name('teams.show');
Route::get('/kempy', [CampController::class, 'index'])->name('camps.index');
Route::post('/kempy/{camp}/registrace', [RegistrationController::class, 'store'])->name('camps.register');
Route::post('/odber', [SubscriberController::class, 'store'])->name('subscribers.store');
Route::get('/o-klubu', [AboutController::class, 'index'])->name('about');
Route::get('/novinky', [PublicArticleController::class, 'index'])->name('articles.index');
Route::get('/novinky/{article}', [PublicArticleController::class, 'show'])->name('articles.show');

/*
|--------------------------------------------------------------------------
| Admin routes (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return match (true) {
            Gate::allows('manage-content') => redirect()->route('admin.articles.index'),
            Gate::allows('manage-club')    => redirect()->route('admin.teams.index'),
            Gate::allows('manage-users')   => redirect()->route('admin.users.index'),
            default                        => redirect()->route('home'),
        };
    })->name('dashboard');

    // Obsah — články a tagy (role: content, nebo admin)
    Route::middleware('can:manage-content')->group(function () {
        Route::resource('articles',   ArticleController::class);
        Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
    });

    // Klub — týmy, hráči, kempy (role: club, nebo admin)
    Route::middleware('can:manage-club')->group(function () {
        Route::post('teams/reorder',  [AdminTeamController::class, 'reorder'])->name('teams.reorder');
        Route::resource('teams',      AdminTeamController::class)->except(['show', 'create', 'edit']);
        Route::post('players/bulk-team', [PlayerController::class, 'bulkMoveTeam'])->name('players.bulk-team');
        Route::resource('players',    PlayerController::class)->except(['show']);
        Route::resource('camps',      AdminCampController::class)->except(['show']);

        // Registrace na kempy
        Route::get('camps/{camp}/registrace',          [AdminRegistrationController::class, 'index'])->name('camps.registrations');
        Route::get('camps/{camp}/registrace/export',   [AdminRegistrationController::class, 'exportCsv'])->name('camps.registrations.export');
        Route::get('registrace/marketing-export',      [AdminRegistrationController::class, 'exportMarketingCsv'])->name('registrations.marketing-export');
        Route::delete('registrace/{registration}',     [AdminRegistrationController::class, 'destroy'])->name('registrations.destroy');

        // Veřejné bruslení
        Route::resource('skating', AdminSkatingController::class)->except(['show'])->names('skating');
    });

    // Uživatelé a role (role: user_manager, nebo admin)
    Route::middleware('can:manage-users')->group(function () {
        Route::post('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
        Route::resource('users', AdminUserController::class)->except(['show']);

        // Odběry (newsletter + registrace kempů) — spravuje stejná role jako uživatele
        Route::get('odbery',                           [AdminSubscriberController::class, 'index'])->name('subscribers.index');
        Route::patch('odbery/{subscriber}/toggle',     [AdminSubscriberController::class, 'toggleMarketing'])->name('subscribers.toggle');
        Route::delete('odbery/{subscriber}',           [AdminSubscriberController::class, 'destroy'])->name('subscribers.destroy');
        Route::get('odbery/export',                    [AdminSubscriberController::class, 'exportCsv'])->name('subscribers.export');
    });
});

/*
|--------------------------------------------------------------------------
| Breeze profile routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
