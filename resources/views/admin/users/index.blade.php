<x-layout>
    <section class="admin-page">
        <p class="eyebrow">ESPACE ADMINISTRATION</p>
        <h1>Comptes clients<span>.</span></h1>
        <p class="admin-intro">Attribuez les rôles avec précaution : un compte administrateur peut gérer le catalogue, les réservations, les messages et les autres rôles.</p>

        @if ($errors->any())
            <div class="form-errors" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="admin-product-list">
            @foreach ($users as $user)
                <article class="admin-product user-row">
                    <div class="user-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</div>
                    <div class="admin-product-info">
                        <h2>{{ $user->name }}</h2>
                        <p>{{ $user->email }} · Inscrit le {{ $user->created_at->format('d/m/Y') }}</p>
                        <span class="product-status {{ $user->isAdmin() ? 'is-visible' : '' }}">{{ $user->isAdmin() ? 'Administrateur' : 'Client' }}</span>
                    </div>
                    <form class="user-role-form" action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <label class="sr-only" for="role-{{ $user->id }}">Rôle de {{ $user->name }}</label>
                        <select id="role-{{ $user->id }}" name="role">
                            <option value="client" @selected(! $user->isAdmin())>Client</option>
                            <option value="admin" @selected($user->isAdmin())>Administrateur</option>
                        </select>
                        <button class="text-link" type="submit">Enregistrer</button>
                    </form>
                </article>
            @endforeach
        </div>

        {{ $users->links() }}
    </section>
</x-layout>
