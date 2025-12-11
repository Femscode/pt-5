<?php

use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/network', [DashboardController::class, 'network'])->middleware(['auth', 'verified'])->name('network');
Route::get('/message', [MessagesController::class, 'index'])->middleware(['auth', 'verified'])->name('message');
Route::get('/event', [DashboardController::class, 'event'])->middleware(['auth', 'verified'])->name('event');
Route::get('/events', [DashboardController::class, 'event'])->middleware(['auth', 'verified']);
Route::get('/events/{event}', [DashboardController::class, 'eventShow'])->middleware(['auth', 'verified'])->name('event.show');
Route::post('/events/{event}/subscribe', [DashboardController::class, 'subscribe'])->middleware(['auth', 'verified'])->name('events.subscribe');

Route::get('/marketplace', [DashboardController::class, 'marketplace'])->middleware(['auth', 'verified'])->name('marketplace');
Route::get('/marketplace/{product}', [DashboardController::class, 'productShow'])->middleware(['auth', 'verified'])->name('marketplace.product');

Route::get('/settings', [DashboardController::class, 'settings'])->middleware(['auth', 'verified'])->name('settings');
Route::post('/settings/update', [DashboardController::class, 'updateSettings'])->middleware(['auth', 'verified'])->name('settings.update');
Route::post('/settings/password', [DashboardController::class, 'updatePassword'])->middleware(['auth', 'verified'])->name('settings.password');


Route::post('/broadcasting/auth', [BroadcastController::class, 'authenticate'])->middleware(['web','auth']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/connections/send', [DashboardController::class, 'sendConnection'])->name('connections.send');
    Route::post('/connections/accept', [DashboardController::class, 'acceptConnection'])->name('connections.accept');
    Route::post('/connections/reject', [DashboardController::class, 'rejectConnection'])->name('connections.reject');
    Route::post('/connections/cancel', [DashboardController::class, 'cancelConnection'])->name('connections.cancel');

    // Messaging API

    Route::post('/v1/conversations/direct', [ConversationsController::class, 'startDirect'])->name('conversations.direct');
    Route::get('/v1/conversations', [ConversationsController::class, 'index'])->name('conversations.index');
    Route::get('/v1/conversations/messages', [ConversationsController::class, 'messages'])->name('conversations.messages');
    Route::get('/v1/conversations/files', [ConversationsController::class, 'files'])->name('conversations.files');
    Route::get('/v1/inbox', [ConversationsController::class, 'inbox'])->name('conversations.inbox');

    Route::post('/v1/messages', [MessagesController::class, 'store'])->name('messages.store');
    Route::delete('/v1/messages', [MessagesController::class, 'destroy'])->name('messages.destroy');
    Route::post('/v1/messages/read', [MessagesController::class, 'markRead'])->name('messages.read');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {});
