<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConversationController extends Controller
{
    public function index(Request $request): View
    {
        $conversations = $request->user()
            ->conversations()
            ->with(['messages' => fn ($query) => $query->latest()->limit(1)])
            ->latest()
            ->get();

        return view('account.conversations.index', compact('conversations'));
    }

    public function create(): View
    {
        return view('account.conversations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use ($request, $validated): void {
            $conversation = $request->user()->conversations()->create([
                'subject' => $validated['subject'],
            ]);
            $conversation->messages()->create([
                'user_id' => $request->user()->id,
                'body' => $validated['body'],
            ]);
        });

        return to_route('account.conversations')->with('status', 'Votre message a été envoyé à la boutique.');
    }

    public function show(Request $request, Conversation $conversation): View
    {
        abort_unless($conversation->user_id === $request->user()->id, 404);

        $conversation->messages()
            ->where('user_id', '!=', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('account.conversations.show', [
            'conversation' => $conversation->load(['messages.user']),
        ]);
    }

    public function reply(Request $request, Conversation $conversation): RedirectResponse
    {
        abort_unless($conversation->user_id === $request->user()->id, 404);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return to_route('account.conversations.show', $conversation)->with('status', 'Votre réponse a été envoyée.');
    }
}
