<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('category')->toString();
        $gender = $request->string('gender')->toString();
        $search = $request->string('q')->toString();

        $products = Product::query()
            ->where('is_active', true)
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->when(in_array($gender, ['femme', 'homme'], true), fn ($query) => $query->where('gender', $gender))
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            }))
            ->latest()
            ->get();

        $categories = Product::query()
            ->where('is_active', true)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('shop.index', [
            'products' => $products,
            'categories' => $categories,
            'category' => $category,
            'gender' => $gender,
            'search' => $search,
            'cartCount' => array_sum($request->session()->get('cart', [])),
        ]);
    }

    public function cart(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::query()->whereIn('id', array_keys($cart))->get()->keyBy('id');
        $items = collect($cart)
            ->map(fn ($quantity, $id) => [
                'product' => $products->get($id),
                'quantity' => $quantity,
            ])
            ->filter(fn ($item) => $item['product'] !== null);

        return view('shop.cart', [
            'items' => $items,
            'total' => $items->sum(fn ($item) => (float) $item['product']->price * $item['quantity']),
            'cartCount' => array_sum($cart),
        ]);
    }

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = min(($cart[$product->id] ?? 0) + 1, 20);
        $request->session()->put('cart', $cart);

        return back()->with('status', "{$product->name} ajouté au panier.");
    }

    public function updateCart(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate(['quantity' => ['required', 'integer', 'between:1,20']]);
        $cart = $request->session()->get('cart', []);

        if (array_key_exists($product->id, $cart)) {
            $cart[$product->id] = $validated['quantity'];
            $request->session()->put('cart', $cart);
        }

        return to_route('cart.index');
    }

    public function removeFromCart(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('status', 'Article retiré du panier.');
    }
}
