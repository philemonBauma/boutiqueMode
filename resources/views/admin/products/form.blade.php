@if ($errors->any())
    <div class="form-errors" role="alert">
        <p>Vérifiez les champs signalés :</p>
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form class="admin-form product-edit-form" action="{{ $action }}" method="POST">
    @csrf
    @if ($method !== 'POST') @method($method) @endif

    <label for="name">Nom du produit</label>
    <input id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>

    <div class="form-row">
        <div>
            <label for="category">Catégorie</label>
            <input id="category" name="category" value="{{ old('category', $product->category ?? '') }}" required>
        </div>
        <div>
            <label for="gender">Collection</label>
            <select id="gender" name="gender" required>
                <option value="femme" @selected(old('gender', $product->gender ?? '') === 'femme')>Femme</option>
                <option value="homme" @selected(old('gender', $product->gender ?? '') === 'homme')>Homme</option>
            </select>
        </div>
    </div>

    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4" required>{{ old('description', $product->description ?? '') }}</textarea>

    <div class="form-row">
        <div>
            <label for="price">Prix (€)</label>
            <input id="price" name="price" type="number" min="0.01" step="0.01" value="{{ old('price', $product->price ?? '') }}" required>
        </div>
        <div>
            <label for="compare_at_price">Ancien prix (€), facultatif</label>
            <input id="compare_at_price" name="compare_at_price" type="number" min="0.01" step="0.01" value="{{ old('compare_at_price', $product->compare_at_price ?? '') }}">
        </div>
    </div>

    <label for="image">Adresse de l’image</label>
    <input id="image" name="image" type="url" value="{{ old('image', $product->image ?? '') }}" required>

    <div class="form-row checkbox-row">
        <label class="checkbox-field"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false))><span>Mettre en avant</span></label>
        <label class="checkbox-field"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))><span>Visible dans la boutique</span></label>
    </div>

    <div class="form-actions">
        <a class="continue-shopping" href="{{ route('admin.products.index') }}">Annuler</a>
        <button class="button button-dark" type="submit">{{ $buttonLabel }} <span aria-hidden="true">→</span></button>
    </div>
</form>
