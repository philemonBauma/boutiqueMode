<x-layout>
    <section class="admin-page">
        <p class="eyebrow">MON ESPACE</p>
        <h1>Bonjour {{ auth()->user()->name }}<span>.</span></h1>
        <p class="admin-intro">Retrouvez vos réservations et vos échanges avec notre équipe.</p>
        <p class="account-role-note">Votre compte est actuellement configuré comme <strong>client</strong>. Les outils de gestion sont réservés aux administrateurs de la boutique.</p>

        <div class="admin-stats">
            <article><span>Mes réservations</span><strong>{{ $reservationCount }}</strong></article>
            <article><span>Messages à lire</span><strong>{{ $unreadMessageCount }}</strong></article>
        </div>

        <div class="account-links">
            <a class="admin-panel" href="{{ route('account.reservations') }}">
                <div><p class="eyebrow">SUIVI</p><h2>Mes réservations</h2><p>Consultez les demandes et leur état d’avancement.</p></div>
                <span class="text-link">Voir →</span>
            </a>
            <a class="admin-panel" href="{{ route('account.conversations') }}">
                <div><p class="eyebrow">CONTACT</p><h2>Mes messages</h2><p>{{ $conversationCount }} conversation(s) avec la boutique.</p></div>
                <span class="text-link">Ouvrir →</span>
            </a>
        </div>
    </section>
</x-layout>
