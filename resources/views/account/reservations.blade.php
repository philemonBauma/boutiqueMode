<x-layout>
    <section class="admin-page">
        <p class="eyebrow">MON ESPACE</p>
        <h1>Mes réservations<span>.</span></h1>
        <p class="admin-intro">Les réservations sont des demandes sans paiement en ligne. La boutique vous contactera pour confirmer la disponibilité.</p>

        @if ($reservations->isEmpty())
            <div class="admin-panel"><p>Vous n’avez pas encore de réservation.</p><a class="text-link" href="{{ route('shop.index') }}">Découvrir la boutique →</a></div>
        @else
            <div class="admin-product-list">
                @foreach ($reservations as $reservation)
                    <article class="reservation-card">
                        <div class="reservation-heading">
                            <h2>Réservation #{{ $reservation->id }}</h2>
                            <span class="product-status">{{ ['pending' => 'En attente', 'confirmed' => 'Confirmée', 'cancelled' => 'Annulée', 'completed' => 'Terminée'][$reservation->status] ?? $reservation->status }}</span>
                        </div>
                        <p class="product-category">{{ $reservation->created_at->format('d/m/Y à H:i') }} · Total {{ number_format((float) $reservation->total, 2, ',', ' ') }} €</p>
                        <ul class="reservation-items">
                            @foreach ($reservation->items as $item)
                                <li>{{ $item->product_name }} × {{ $item->quantity }} — {{ number_format((float) $item->unit_price * $item->quantity, 2, ',', ' ') }} €</li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</x-layout>
