<x-layout>
    <section class="admin-page">
        <div class="admin-heading">
            <div>
                <p class="eyebrow">ESPACE ADMINISTRATION</p>
                <h1>Bonjour {{ auth()->user()->name }}<span>.</span></h1>
                <p class="admin-intro">Gérez les produits, réservations et messages de la boutique PHILINDA.</p>
            </div>
            <a class="button button-dark" href="{{ route('admin.products.create') }}">Ajouter un produit <span aria-hidden="true">+</span></a>
        </div>

        <div class="admin-stats">
            <article><span>Produits au catalogue</span><strong>{{ $productCount }}</strong></article>
            <article><span>Produits visibles en boutique</span><strong>{{ $activeProductCount }}</strong></article>
        </div>

        <div class="admin-panel">
            <div>
                <p class="eyebrow">CATALOGUE</p>
                <h2>Vos produits</h2>
                <p>Ajoutez, modifiez, masquez ou supprimez les articles affichés dans la boutique.</p>
            </div>
            <a class="text-link" href="{{ route('admin.products.index') }}">Gérer les produits →</a>
        </div>
        <div class="account-links">
            <a class="admin-panel" href="{{ route('admin.reservations.index') }}">
                <div><p class="eyebrow">COMMANDES CLIENTS</p><h2>Réservations</h2><p>Consultez les demandes et mettez à jour leur statut.</p></div>
                <span class="text-link">Gérer →</span>
            </a>
            <a class="admin-panel" href="{{ route('admin.conversations.index') }}">
                <div><p class="eyebrow">SERVICE CLIENT</p><h2>Messages</h2><p>Répondez aux conversations des clients.</p></div>
                <span class="text-link">Ouvrir →</span>
            </a>
            <a class="admin-panel" href="{{ route('admin.users.index') }}">
                <div><p class="eyebrow">ACCÈS AU SITE</p><h2>Comptes clients</h2><p>Consultez les inscriptions et gérez les rôles.</p></div>
                <span class="text-link">Gérer →</span>
            </a>
        </div>
    </section>
</x-layout>
