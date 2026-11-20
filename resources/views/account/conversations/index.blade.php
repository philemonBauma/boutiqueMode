<x-layout>
    <section class="admin-page">
        <div class="admin-heading">
            <div><p class="eyebrow">MON ESPACE</p><h1>Mes messages<span>.</span></h1></div>
            <a class="button button-dark" href="{{ route('account.conversations.create') }}">Écrire à la boutique <span aria-hidden="true">+</span></a>
        </div>

        @if ($conversations->isEmpty())
            <div class="admin-panel"><p>Vous n’avez pas encore de conversation.</p></div>
        @else
            <div class="admin-product-list">
                @foreach ($conversations as $conversation)
                    <a class="conversation-row" href="{{ route('account.conversations.show', $conversation) }}">
                        <div><h2>{{ $conversation->subject }}</h2><p>{{ $conversation->messages->first()?->body }}</p></div>
                        <span class="text-link">Ouvrir →</span>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</x-layout>
