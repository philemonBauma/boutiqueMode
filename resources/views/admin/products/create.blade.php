<x-layout>
    <section class="admin-page admin-form-page">
        <p class="eyebrow">ESPACE ADMINISTRATION</p>
        <h1>Ajouter un produit<span>.</span></h1>
        @include('admin.products.form', ['action' => route('admin.products.store'), 'method' => 'POST', 'buttonLabel' => 'Créer le produit'])
    </section>
</x-layout>
