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
                    <div><span>Sous-total</span><strong>{{ number_format($total, 2, ',', ' ') }} €</strong></div>
                    <div><span>Livraison</span><span>Calculée à l'étape suivante</span></div>
                    <p>La livraison est offerte dès 100 € d'achat.</p>
                    <button class="button button-dark checkout-button" type="button" disabled title="Le paiement en ligne sera disponible prochainement">Paiement bientôt disponible</button>
                    <a class="continue-shopping" href="{{ route('shop.index') }}">← Continuer mes achats</a>
                </aside>
            </div>
        @endif
    </section>
</x-layout>
