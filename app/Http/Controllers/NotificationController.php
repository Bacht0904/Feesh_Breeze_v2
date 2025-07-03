<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        dd($request);
        $user = $request->user();

        // Đánh dấu tất cả là đã đọc (nếu muốn)
        $user->unreadNotifications->markAsRead();

        return view('admin.notifications', [
            'notifications' => $user->notifications()->latest()->paginate(10),
        ]);
    }
}
