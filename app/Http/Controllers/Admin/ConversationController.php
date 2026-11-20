<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationController extends Controller
{
    public function index(): View
    {
        return view('admin.conversations.index', [
            'conversations' => Conversation::with(['user', 'messages' => fn ($query) => $query->latest()->limit(1)])
                ->withCount(['messages as unread_count' => fn ($query) => $query->whereNull('read_at')->whereHas('user', fn ($user) => $user->where('role', '!=', 'admin'))])
                ->latest()
                ->get(),
        ]);
    }

    public function show(Conversation $conversation): View
    {
        $conversation->messages()
            ->whereHas('user', fn ($query) => $query->where('role', '!=', 'admin'))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.conversations.show', [
            'conversation' => $conversation->load(['user', 'messages.user']),
        ]);
    }

    public function reply(Request $request, Conversation $conversation): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return to_route('admin.conversations.show', $conversation)->with('status', 'Votre réponse a été envoyée.');
    }
}
