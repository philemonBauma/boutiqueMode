<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(Request $request): View
    {
        return view('account.dashboard', [
            'reservationCount' => $request->user()->reservations()->count(),
            'conversationCount' => $request->user()->conversations()->count(),
            'unreadMessageCount' => $request->user()->conversations()
                ->withCount(['messages as unread_count' => fn ($query) => $query
                    ->where('user_id', '!=', $request->user()->id)
                    ->whereNull('read_at')])
                ->get()
                ->sum('unread_count'),
        ]);
    }
}
