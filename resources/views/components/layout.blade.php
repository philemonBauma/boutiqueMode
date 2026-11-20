@props(['cartCount' => 0])

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f5f0">
    <title>{{ $title ?? 'PHILINDA — Le vestiaire essentiel' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="announcement">L'elegance à très bon prix chez PHILINDA</div>
    <header class="site-header">
        <div class="header-inner">
            <a class="wordmark" href="{{ route('homePage') }}" aria-label="Atelier, accueil">PHILINDA<span>.</span></a>
            <button class="menu-toggle" type="button" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="main-nav">
                <span></span><span></span>
            </button>
            <nav class="main-nav" id="main-nav" aria-label="Navigation principale">
                <a href="{{ route('shop.index', ['gender' => 'femme']) }}">Femme</a>
                <a href="{{ route('shop.index', ['gender' => 'homme']) }}">Homme</a>
                <a href="{{ route('shop.index') }}">Nouvelle collection</a>
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">Administration</a>
                        <a href="{{ route('admin.reservations.index') }}">Réservations</a>
                        <a href="{{ route('admin.conversations.index') }}">Messages</a>
                        <a href="{{ route('admin.users.index') }}">Comptes</a>
                    @else
                        <a href="{{ route('account.dashboard') }}">Mon compte</a>
                        <a href="{{ route('account.reservations') }}">Mes réservations</a>
                        <a href="{{ route('account.conversations') }}">Mes messages</a>
                    @endif
                @else
                    <a href="{{ route('login') }}">Connexion</a>
                    <a href="{{ route('register') }}">Créer un compte</a>
                @endauth
            </nav>
            <a class="cart-link" href="{{ route('cart.index') }}" aria-label="Panier, {{ $cartCount }} articles">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8h14l1 13H4L5 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg>
                <span>Panier</span><span class="cart-count">{{ $cartCount }}</span>
            </a>
            @auth
                <form class="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Déconnexion</button>
                </form>
            @endauth
        </div>
    </header>

    @auth
        @if (auth()->user()->isAdmin())
            <nav class="admin-toolbar" aria-label="Raccourcis d’administration">
                <strong>Administration</strong>
                <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
                <a href="{{ route('admin.products.index') }}">Produits</a>
                <a href="{{ route('admin.products.create') }}">Ajouter un produit</a>
                <a href="{{ route('admin.reservations.index') }}">Réservations</a>
                <a href="{{ route('admin.conversations.index') }}">Messages</a>
                <a href="{{ route('admin.users.index') }}">Comptes et rôles</a>
            </nav>
        @endif
    @endauth

    @if (session('status'))
        <div class="flash-message" role="status">{{ session('status') }}</div>
    @endif

    <main>{{ $slot }}</main>

    <footer class="site-footer">
        <a class="wordmark" href="{{ route('homePage') }}">PHILINDA<span>.</span></a>
        <p>Des essentiels bien pensés, faits pour durer.</p>
        <span>© {{ date('Y') }} PHILINDA. Tous droits réservés.</span>
    </footer>
</body>
</html>
