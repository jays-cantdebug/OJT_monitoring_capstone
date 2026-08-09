<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $items = $request->user()->notifications->map(fn ($notification) => [
            'text' => $notification->data['text'],
            'time' => $notification->created_at->diffForHumans(),
            'unread' => $notification->read_at === null,
            'compliance' => $notification->data['type'] === 'alert',
            'type' => $notification->data['type'],
        ]);

        return view('dean.notifications', compact('items'));
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return redirect()->route('dean.notifications');
    }
}
