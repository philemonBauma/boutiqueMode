<x-layout>
    <section class="admin-page">
        <p class="eyebrow">ESPACE ADMINISTRATION</p>
        <h1>Messages clients<span>.</span></h1>

        @if ($conversations->isEmpty())
            <div class="admin-panel"><p>Aucun message pour le moment.</p></div>
        @else
            <div class="admin-product-list">
                @foreach ($conversations as $conversation)
                    <a class="conversation-row" href="{{ route('admin.conversations.show', $conversation) }}">
                        <div>
                            <h2>{{ $conversation->subject }}</h2>
                            <p>{{ $conversation->user->name }} · {{ $conversation->user->email }}</p>
                            <p>{{ $conversation->messages->first()?->body }}</p>
                        </div>
                        @if ($conversation->unread_count > 0)
                            <span class="unread-badge">{{ $conversation->unread_count }} nouveau(x)</span>
                        @else
                            <span class="text-link">Ouvrir →</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</x-layout>
