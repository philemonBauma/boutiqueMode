<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $reservations = $request->user()
            ->reservations()
            ->with('items')
            ->latest()
            ->get();

        return view('account.reservations', compact('reservations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        if (! is_array($cart) || $cart === []) {
            return to_route('cart.index')->withErrors([
                'cart' => 'Votre panier est vide ou contient des articles indisponibles.',
            ]);
        }

        $productIds = array_keys($cart);
        $products = Product::query()
            ->whereIn('id', $productIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $items = collect($cart)
            ->map(fn ($quantity, $id) => [
                'product' => $products->get($id),
                'quantity' => filter_var($quantity, FILTER_VALIDATE_INT, [
                    'options' => ['min_range' => 1, 'max_range' => 20],
                ]),
            ])
            ->filter(fn ($item) => $item['product'] !== null && $item['quantity'] !== false);

        if ($items->isEmpty() || $items->count() !== count($cart)) {
            return to_route('cart.index')->withErrors([
                'cart' => 'Votre panier est vide ou contient des articles indisponibles.',
            ]);
        }

        $total = $items->sum(fn ($item) => (int) round((float) $item['product']->price * 100) * $item['quantity']) / 100;

        DB::transaction(function () use ($request, $items, $total): void {
            $reservation = $request->user()->reservations()->create([
                'status' => 'pending',
                'total' => $total,
            ]);

            foreach ($items as $item) {
                $reservation->items()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'unit_price' => $item['product']->price,
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        $request->session()->forget('cart');

        return to_route('account.reservations')->with('status', 'Votre demande de réservation a été envoyée.');
    }
}
