<x-layout>
    <section class="auth-page">
        <div class="auth-card">
            <p class="eyebrow">VOTRE ESPACE PHILINDA</p>
            <h1>Bon retour<span>.</span></h1>
            <p class="auth-intro">Connectez-vous pour retrouver votre espace et suivre vos échanges avec la boutique.</p>

            @if ($errors->any())
                <div class="form-errors" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="admin-form" action="{{ route('login.store') }}" method="POST">
                @csrf
                <label for="email">Adresse e-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>

                <label for="password">Mot de passe</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>

                <label class="checkbox-field">
                    <input type="checkbox" name="remember" value="1">
                    <span>Rester connecté</span>
                </label>

                <button class="button button-dark" type="submit">Se connecter <span aria-hidden="true">→</span></button>
            </form>

            <p class="auth-footnote">L’accès à l’administration est réservé aux comptes autorisés.</p>
            <p class="auth-footnote">Pas encore de compte ? <a class="text-link" href="{{ route('register') }}">Créer un compte client</a></p>
            <a class="continue-shopping" href="{{ route('shop.index') }}">Continuer sans se connecter →</a>
        </div>
    </section>
</x-layout>
