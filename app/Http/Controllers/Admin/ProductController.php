<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'activeProductCount' => Product::where('is_active', true)->count(),
        ]);
    }

    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::query()->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);
        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        Product::create($validated);

        return to_route('admin.products.index')->with('status', 'Produit ajouté à la boutique.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateProduct($request);
        $validated['slug'] = $this->uniqueSlug($validated['name'], $product);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        return to_route('admin.products.index')->with('status', 'Produit mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('admin.products.index')->with('status', 'Produit supprimé.');
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['femme', 'homme'])],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'gt:0', 'max:999999.99'],
            'compare_at_price' => ['nullable', 'numeric', 'gt:0', 'max:999999.99'],
            'image' => ['required', 'url', 'max:255'],
        ]);
    }

    private function uniqueSlug(string $name, ?Product $product = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $suffix = 2;

        while (Product::query()
            ->where('slug', $slug)
            ->when($product, fn ($query) => $query->whereKeyNot($product->getKey()))
            ->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
