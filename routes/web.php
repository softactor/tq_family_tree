<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        // If user is logged in, redirect to dashboard
        return redirect()->route('admin.dashboard');
    }
    // If not logged in, redirect to login
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('family')->name('family.')->group(function () {
        Route::get('/members', [FamilyController::class, 'members'])->name('members');
        Route::get('/add', [FamilyController::class, 'add'])->name('add');
        Route::post('/store', [FamilyController::class, 'store'])->name('store');
        Route::get('/{id}', [FamilyController::class, 'show'])->name('show'); // To fetch details for editing
        Route::put('/{id}', [FamilyController::class, 'update'])->name('update'); // Fixed parameter "{id}" here
        Route::delete('/{id}', [FamilyController::class, 'destroy'])->name('destroy');

        Route::get('/{id}/events', [FamilyController::class, 'fetchEvents'])->name('events.fetch');
        Route::post('/events', [FamilyController::class, 'storeEvent'])->name('events.store');
        Route::delete('/events/{id}', [FamilyController::class, 'deleteEvent'])->name('events.delete');


        Route::get('/{id}/relationships', [FamilyController::class, 'fetchRelationships'])->name('relationships.fetch');
        Route::post('/relationships', [FamilyController::class, 'storeRelationship'])->name('relationships.store');
        Route::delete('/relationships/{id}', [FamilyController::class, 'deleteRelationship'])->name('relationships.delete');

    });
});