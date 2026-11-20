<x-layout>
    <section class="admin-page admin-form-page">
        <p class="eyebrow">MON ESPACE</p>
        <h1>Écrire à la boutique<span>.</span></h1>
        @include('account.conversations.form', ['action' => route('account.conversations.store'), 'buttonLabel' => 'Envoyer le message'])
    </section>
</x-layout>
