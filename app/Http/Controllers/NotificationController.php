<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\OrderCancelRequested;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $user->unreadNotifications->markAsRead();

        return view('admin.notifications', [
            'notifications' => $user->notifications()->latest()->paginate(10),
        ]);
    }

     public function indexUser(Request $request)
    {
        $user = $request->user();

        $user->unreadNotifications->markAsRead();

        return view('user.notifications', [
            'notifications' => $user->notifications()->latest()->paginate(10),
        ]);
    }
}
