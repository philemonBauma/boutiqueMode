<x-layout>
    <section class="admin-page">
        <p class="eyebrow">MESSAGE CLIENT · {{ $conversation->user->name }} ({{ $conversation->user->email }})</p>
        <h1>{{ $conversation->subject }}<span>.</span></h1>
        <div class="message-thread">
            @foreach ($conversation->messages as $message)
                <article class="thread-message {{ $message->user->isAdmin() ? 'from-shop' : 'from-customer' }}">
                    <p class="thread-author">{{ $message->user->name }} · {{ $message->created_at->format('d/m/Y H:i') }}</p>
                    <p>{{ $message->body }}</p>
                </article>
            @endforeach
        </div>
        @include('account.conversations.form', ['action' => route('admin.conversations.reply', $conversation), 'buttonLabel' => 'Répondre', 'conversation' => $conversation])
    </section>
</x-layout>
