<x-layout>
    <section class="admin-page">
        <div class="admin-heading">
            <div>
                <p class="eyebrow">ESPACE ADMINISTRATION</p>
                <h1>Catalogue<span>.</span></h1>
            </div>
            <a class="button button-dark" href="{{ route('admin.products.create') }}">Ajouter un produit <span aria-hidden="true">+</span></a>
        </div>

        @if ($products->isEmpty())
            <div class="admin-panel"><p>Aucun produit pour le moment.</p></div>
        @else
            <div class="admin-product-list">
                @foreach ($products as $product)
                    <article class="admin-product">
                        <img src="{{ $product->image }}" alt="">
                        <div class="admin-product-info">
                            <h2>{{ $product->name }}</h2>
                            <p>{{ $product->category }} · {{ ucfirst($product->gender) }} · {{ number_format((float) $product->price, 2, ',', ' ') }} €</p>
                            <span class="product-status {{ $product->is_active ? 'is-visible' : '' }}">{{ $product->is_active ? 'Visible' : 'Masqué' }}</span>
                        </div>
                        <div class="admin-product-actions">
                            <a class="text-link" href="{{ route('admin.products.edit', $product) }}">Modifier</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-button" type="submit">Supprimer</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</x-layout>
