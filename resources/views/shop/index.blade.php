<x-layout :cart-count="$cartCount">
    <section class="hero">
        <div class="hero-copy">
            <p class="eyebrow">COLLECTION AUTOMNE · 2026</p>
            <h1>Le style, <em>tout simplement.</em></h1>
            <p class="hero-description">Des pièces essentielles, des matières choisies et des coupes qui vous ressemblent. Découvrez le vestiaire Atelier.</p>
            <a class="button button-dark" href="#collection">Découvrir la collection <span aria-hidden="true">→</span></a>
            <div class="hero-note"><span class="hero-note-mark">✳</span> Pensé pour aujourd'hui,<br>fait pour durer.</div>
        </div>
        <div class="hero-image" role="img" aria-label="Collection de vêtements Atelier">
            <div class="hero-image-label">LES ESSENTIELS<br><span>NO. 01 — 26</span></div>
        </div>
    </section>

    <section class="collection-section" id="collection">
        <div class="section-heading">
            <div>
                <p class="eyebrow">LE VESTIAIRE ATELIER</p>
                <h2>{{ $gender === 'femme' ? 'La sélection femme' : ($gender === 'homme' ? 'La sélection homme' : 'Les pièces du moment') }}</h2>
            </div>
            <span class="result-count">{{ $products->count() }} {{ $products->count() > 1 ? 'articles' : 'article' }}</span>
        </div>

        <div class="shop-tools">
            <div class="filter-links" aria-label="Filtrer par collection">
                <a class="filter-chip {{ $gender === '' ? 'is-active' : '' }}" href="{{ route('shop.index', array_filter(['q' => $search, 'category' => $category])) }}">Tout voir</a>
                <a class="filter-chip {{ $gender === 'femme' ? 'is-active' : '' }}" href="{{ route('shop.index', array_filter(['gender' => 'femme', 'q' => $search, 'category' => $category])) }}">Femme</a>
                <a class="filter-chip {{ $gender === 'homme' ? 'is-active' : '' }}" href="{{ route('shop.index', array_filter(['gender' => 'homme', 'q' => $search, 'category' => $category])) }}">Homme</a>
                @foreach ($categories as $categoryOption)
                    <a class="filter-chip {{ $category === $categoryOption ? 'is-active' : '' }}" href="{{ route('shop.index', array_filter(['gender' => $gender, 'category' => $categoryOption, 'q' => $search])) }}">{{ $categoryOption }}</a>
                @endforeach
            </div>
            <form class="search-form" action="{{ route('shop.index') }}" method="GET" role="search">
                @if ($gender !== '')<input type="hidden" name="gender" value="{{ $gender }}">@endif
                @if ($category !== '')<input type="hidden" name="category" value="{{ $category }}">@endif
                <label class="sr-only" for="shop-search">Rechercher un article</label>
                <input id="shop-search" type="search" name="q" value="{{ $search }}" placeholder="Rechercher…">
                <button type="submit" aria-label="Rechercher">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/></svg>
                </button>
            </form>
        </div>

        @if ($products->isNotEmpty())
            <div class="product-grid">
                @foreach ($products as $product)
                    <article class="product-card">
                        <a class="product-image-link" href="{{ route('shop.index', ['gender' => $product->gender, 'category' => $product->category]) }}">
                            <img class="product-image" src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy">
                            @if ($product->compare_at_price)
                                <span class="product-badge">ÉDITION LIMITÉE</span>
                            @elseif ($product->is_featured)
                                <span class="product-badge">COUP DE CŒUR</span>
                            @endif
                        </a>
                        <div class="product-info">
                            <div>
                                <p class="product-category">{{ $product->category }} <span>·</span> {{ ucfirst($product->gender) }}</p>
                                <h3>{{ $product->name }}</h3>
                            </div>
                            <div class="product-price">
                                @if ($product->compare_at_price)
                                    <span class="old-price">{{ number_format((float) $product->compare_at_price, 2, ',', ' ') }} €</span>
                                @endif
                                <span>{{ number_format((float) $product->price, 2, ',', ' ') }} €</span>
                            </div>
                        </div>
                        <p class="product-description">{{ $product->description }}</p>
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <button class="add-button" type="submit">Ajouter au panier <span aria-hidden="true">+</span></button>
                        </form>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty-results">
                <span aria-hidden="true">⌕</span>
                <h3>Aucun article trouvé</h3>
                <p>Essayez un autre mot-clé ou explorez toute notre collection.</p>
                <a class="button button-dark" href="{{ route('shop.index') }}">Voir toute la collection</a>
            </div>
        @endif
    </section>

    <section class="promise-strip">
        <div><span>01</span><p><strong>Matières choisies</strong><br>Une attention portée à chaque détail.</p></div>
        <div><span>02</span><p><strong>Livraison offerte</strong><br>Dès 100 € d'achat.</p></div>
        <div><span>03</span><p><strong>Retour facile</strong><br>Vous avez 30 jours pour changer d'avis.</p></div>
    </section>
</x-layout>
