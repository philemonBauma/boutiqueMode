<x-layout :cart-count="$cartCount">
    <section class="cart-page">
        <p class="eyebrow">VOTRE SÉLECTION</p>
        <h1>Votre panier<span>.</span></h1>

        @if ($items->isEmpty())
            <div class="empty-cart">
                <p>Votre panier attend ses premières pièces.</p>
                <a class="button button-dark" href="{{ route('shop.index') }}">Explorer la collection <span aria-hidden="true">→</span></a>
            </div>
        @else
            <div class="cart-layout">
                <div class="cart-items">
                    @foreach ($items as $item)
                        <article class="cart-item">
                            <img src="{{ $item['product']->image }}" alt="{{ $item['product']->name }}">
                            <div class="cart-item-info">
                                <p class="product-category">{{ $item['product']->category }} · {{ ucfirst($item['product']->gender) }}</p>
                                <h2>{{ $item['product']->name }}</h2>
                                <p>{{ number_format((float) $item['product']->price, 2, ',', ' ') }} €</p>
                                <div class="cart-actions">
                                    <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="quantity-form">
                                        @csrf
                                        @method('PATCH')
                                        <label for="quantity-{{ $item['product']->id }}">Quantité</label>
                                        <input id="quantity-{{ $item['product']->id }}" type="number" name="quantity" min="1" max="20" value="{{ $item['quantity'] }}">
                                        <button type="submit">Mettre à jour</button>
                                    </form>
                                    <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="remove-button" type="submit">Retirer</button>
                                    </form>
                                </div>
                            </div>
                            <strong class="cart-line-total">{{ number_format((float) $item['product']->price * $item['quantity'], 2, ',', ' ') }} €</strong>
                        </article>
                    @endforeach
                </div>
                <aside class="cart-summary">
                    <h2>Récapitulatif</h2>
                    @if ($errors->has('cart'))
                        <div class="form-errors" role="alert">{{ $errors->first('cart') }}</div>
                    @endif
                    <div><span>Sous-total</span><strong>{{ number_format($total, 2, ',', ' ') }} €</strong></div>
                    <p>Envoyez votre demande à la boutique. Aucun paiement en ligne n’est effectué à cette étape ; la disponibilité sera confirmée par l’équipe.</p>
                    @auth
                        @if (! auth()->user()->isAdmin())
                            <form action="{{ route('account.reservations.store') }}" method="POST">
                                @csrf
                                <button class="button button-dark checkout-button" type="submit">Demander une réservation <span aria-hidden="true">→</span></button>
                            </form>
                        @else
                            <a class="button button-dark checkout-button" href="{{ route('admin.reservations.index') }}">Voir les réservations</a>
                        @endif
                    @else
                        <a class="button button-dark checkout-button" href="{{ route('login') }}">Connectez-vous pour réserver</a>
                        <a class="continue-shopping" href="{{ route('register') }}">Créer un compte client</a>
                    @endauth
                    <a class="continue-shopping" href="{{ route('shop.index') }}">← Continuer mes achats</a>
                </aside>
            </div>
        @endif
    </section>
</x-layout>
