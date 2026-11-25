<?php

use App\Models\Conversation;
use App\Models\Event;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $totalUsers = User::count();
    $totalEvents = Event::count();
    $totalConversations = Conversation::count();
    $totalMessages = Message::count();

    $events = Event::with('participants')->orderBy('start_time', 'asc')->take(5)->get();
    $users = User::latest()->take(6)->get();

    return view('admin_dashboard', compact(
        'totalUsers',
        'totalEvents',
        'totalConversations',
        'totalMessages',
        'events',
        'users'
    ));
});
