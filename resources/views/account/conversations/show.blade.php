<x-layout>
    <section class="admin-page">
        <p class="eyebrow">CONVERSATION</p>
        <h1>{{ $conversation->subject }}<span>.</span></h1>
        <div class="message-thread">
            @foreach ($conversation->messages as $message)
                <article class="thread-message {{ $message->user_id === auth()->id() ? 'from-customer' : 'from-shop' }}">
                    <p class="thread-author">{{ $message->user->name }} · {{ $message->created_at->format('d/m/Y H:i') }}</p>
                    <p>{{ $message->body }}</p>
                </article>
            @endforeach
        </div>
        @include('account.conversations.form', ['action' => route('account.conversations.reply', $conversation), 'buttonLabel' => 'Répondre'])
    </section>
</x-layout>
