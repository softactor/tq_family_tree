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
        return redirect()->route('admin.family.members');
    }
    // If not logged in, redirect to login
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('family')->name('family.')->group(function () {
        // Family Member CRUD Routes
        Route::get('/members', [FamilyController::class, 'members'])->name('members'); // List all members
        Route::post('/store', [FamilyController::class, 'store'])->name('store'); // Add new member
        Route::get('/{id}', [FamilyController::class, 'show'])->name('show'); // Fetch details for editing
        Route::put('/{id}', [FamilyController::class, 'update'])->name('update'); // Update existing member
        Route::delete('/{id}', [FamilyController::class, 'destroy'])->name('destroy'); // Delete a member
        
        // Fetch family members data (e.g., for AJAX tables)
        Route::get('/members/data', [FamilyController::class, 'getMembers'])->name('members.data');

        // Event Management Routes
        Route::get('/{id}/events', [FamilyController::class, 'fetchEvents'])->name('events.fetch'); // Fetch all events for member
        Route::post('/{id}/events', [FamilyController::class, 'storeEvent'])->name('events.store'); // Add event for member
        Route::delete('/events/{id}', [FamilyController::class, 'deleteEvent'])->name('events.delete'); // Delete specific event

        // Relationship Management Routes
        Route::get('/{id}/relationships', [FamilyController::class, 'fetchRelationships'])->name('relationships.fetch'); // Fetch all relationships of member
        Route::post('/{id}/relationships', [FamilyController::class, 'storeRelationship'])->name('relationships.store'); // Add a relationship for member
        Route::delete('/relationships/{id}', [FamilyController::class, 'deleteRelationship'])->name('relationships.delete'); // Delete a specific relationship

        // Family Tree Route
        Route::get('/tree/vizualization', [FamilyController::class, 'fetchFamilyTree'])->name('tree.fetch'); // Fetch tree data
        Route::get('/tree/visualization', [FamilyController::class, 'treeVisualization'])->name('tree.visualization');
        // Add this route to your existing family routes
        Route::get('/{id}/tree', [FamilyController::class, 'viewMemberTree'])->name('member.tree');
    });
});