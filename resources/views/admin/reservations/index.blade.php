<x-layout>
    <section class="admin-page">
        <p class="eyebrow">ESPACE ADMINISTRATION</p>
        <h1>Réservations<span>.</span></h1>
        <p class="admin-intro">Vérifiez les articles demandés puis confirmez, annulez ou clôturez chaque réservation.</p>

        @if ($reservations->isEmpty())
            <div class="admin-panel"><p>Aucune réservation pour le moment.</p></div>
        @else
            <div class="admin-product-list">
                @foreach ($reservations as $reservation)
                    <article class="reservation-card">
                        <div class="reservation-heading">
                            <div>
                                <h2>Réservation #{{ $reservation->id }}</h2>
                                <p class="product-category">{{ $reservation->user->name }} · {{ $reservation->user->email }} · {{ $reservation->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <strong>{{ number_format((float) $reservation->total, 2, ',', ' ') }} €</strong>
                        </div>
                        <ul class="reservation-items">
                            @foreach ($reservation->items as $item)
                                <li>{{ $item->product_name }} × {{ $item->quantity }} — {{ number_format((float) $item->unit_price * $item->quantity, 2, ',', ' ') }} €</li>
                            @endforeach
                        </ul>
                        <form class="reservation-status-form" action="{{ route('admin.reservations.update', $reservation) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <label for="status-{{ $reservation->id }}">Statut</label>
                            <select id="status-{{ $reservation->id }}" name="status">
                                @foreach (['pending' => 'En attente', 'confirmed' => 'Confirmée', 'cancelled' => 'Annulée', 'completed' => 'Terminée'] as $value => $label)
                                    <option value="{{ $value }}" @selected($reservation->status === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button class="text-link" type="submit">Enregistrer</button>
                        </form>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</x-layout>
