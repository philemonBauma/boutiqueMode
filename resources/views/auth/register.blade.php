<x-layout>
    <section class="auth-page">
        <div class="auth-card">
            <p class="eyebrow">VOTRE ESPACE PHILINDA</p>
            <h1>Créer un compte<span>.</span></h1>
            <p class="auth-intro">Suivez vos réservations et échangez avec notre équipe.</p>

            @if ($errors->any())
                <div class="form-errors" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="admin-form" action="{{ route('register.store') }}" method="POST">
                @csrf
                <label for="name">Nom complet</label>
                <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required autofocus>

                <label for="email">Adresse e-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>

                <label for="password">Mot de passe</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required>

                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>

                <button class="button button-dark" type="submit">Créer mon compte <span aria-hidden="true">→</span></button>
            </form>

            <p class="auth-footnote">Déjà inscrit ? <a class="text-link" href="{{ route('login') }}">Se connecter</a></p>
        </div>
    </section>
</x-layout>
