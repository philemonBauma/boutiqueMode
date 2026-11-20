<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()->orderBy('name')->paginate(20),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['client', 'admin'])],
        ]);

        DB::transaction(function () use ($request, $user, $validated): void {
            $user = User::query()->lockForUpdate()->findOrFail($user->id);

            if ($user->id === $request->user()->id && $validated['role'] !== 'admin') {
                throw ValidationException::withMessages([
                    'role' => 'Vous ne pouvez pas retirer votre propre accès administrateur.',
                ]);
            }

            if ($user->isAdmin() && $validated['role'] === 'client') {
                $adminCount = User::query()
                    ->where('role', 'admin')
                    ->lockForUpdate()
                    ->count();

                if ($adminCount <= 1) {
                    throw ValidationException::withMessages([
                        'role' => 'Le dernier administrateur ne peut pas être rétrogradé.',
                    ]);
                }
            }

            $user->forceFill(['role' => $validated['role']])->save();
        });

        return to_route('admin.users.index')->with('status', 'Rôle du compte mis à jour.');
    }
}
