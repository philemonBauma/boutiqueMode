<x-layout>
    <section class="admin-page admin-form-page">
        <p class="eyebrow">ESPACE ADMINISTRATION</p>
        <h1>Modifier le produit<span>.</span></h1>
        @include('admin.products.form', ['action' => route('admin.products.update', $product), 'method' => 'PUT', 'buttonLabel' => 'Enregistrer', 'product' => $product])
    </section>
</x-layout>
