<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(): View
    {
        return view('admin.reservations.index', [
            'reservations' => Reservation::with(['user', 'items'])->latest()->get(),
        ]);
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled', 'completed'])],
        ]);

        $reservation->update($validated);

        return to_route('admin.reservations.index')->with('status', 'Statut de la réservation mis à jour.');
    }
}
