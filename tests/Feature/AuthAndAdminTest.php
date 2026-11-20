<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_starts_with_login_and_shop_remains_public(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Se connecter');

        $this->get(route('shop.index'))->assertOk();
    }

    public function test_login_redirects_customers_and_admins_to_their_own_spaces(): void
    {
        $customer = User::factory()->create([
            'email' => 'client@example.com',
            'password' => 'password',
        ]);
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $admin->forceFill(['role' => 'admin'])->save();

        $this->post(route('login.store'), [
            'email' => $customer->email,
            'password' => 'password',
        ])->assertRedirect(route('account.dashboard'));

        $this->post(route('logout'));

        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Ajouter un produit')
            ->assertSee('Comptes et rôles');
    }

    public function test_only_admins_can_create_products(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('admin.products.index'))
            ->assertForbidden();

        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Robe nouvelle',
                'category' => 'Robes',
                'gender' => 'femme',
                'description' => 'Une robe pour les beaux jours.',
                'price' => '89.00',
                'image' => 'https://example.com/robe.jpg',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Robe nouvelle',
            'slug' => 'robe-nouvelle',
            'is_active' => true,
        ]);

        $this->assertSame(1, Product::count());
    }

    public function test_customers_can_register_with_the_customer_role(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Nouvelle cliente',
            'email' => 'nouvelle@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('account.dashboard'));

        $this->assertAuthenticated();
        $this->get(route('account.dashboard'))->assertOk()->assertSee('Mes réservations');
        $this->assertDatabaseHas('users', [
            'email' => 'nouvelle@example.com',
            'role' => 'client',
        ]);
    }

    public function test_customer_can_submit_a_cart_as_a_reservation_and_admin_can_update_it(): void
    {
        $customer = User::factory()->create();
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $product = Product::create([
            'name' => 'Manteau d’hiver',
            'slug' => 'manteau-hiver',
            'category' => 'Manteaux',
            'gender' => 'femme',
            'description' => 'Un manteau chaud.',
            'price' => '120.00',
            'image' => 'https://example.com/manteau.jpg',
            'is_active' => true,
        ]);

        $this->actingAs($customer)
            ->withSession(['cart' => [$product->id => 2]])
            ->post(route('account.reservations.store'))
            ->assertRedirect(route('account.reservations'));

        $this->assertDatabaseHas('reservations', [
            'user_id' => $customer->id,
            'status' => 'pending',
            'total' => '240.00',
        ]);
        $this->assertDatabaseHas('reservation_items', [
            'product_id' => $product->id,
            'product_name' => 'Manteau d’hiver',
            'quantity' => 2,
        ]);
        $this->assertFalse(session()->has('cart'));
        $this->get(route('account.reservations'))->assertOk()->assertSee('Manteau d’hiver');

        $reservationId = $customer->reservations()->firstOrFail()->id;
        $this->actingAs($admin)
            ->patch(route('admin.reservations.update', $reservationId), ['status' => 'confirmed'])
            ->assertRedirect(route('admin.reservations.index'));

        $this->assertDatabaseHas('reservations', [
            'id' => $reservationId,
            'status' => 'confirmed',
        ]);
        $this->get(route('admin.reservations.index'))->assertOk()->assertSee('Manteau d’hiver');
    }

    public function test_customer_and_admin_can_exchange_messages_but_customers_cannot_read_each_others_conversations(): void
    {
        $customer = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $this->actingAs($customer)
            ->post(route('account.conversations.store'), [
                'subject' => 'Question sur une commande',
                'body' => 'Bonjour, pouvez-vous me renseigner ?',
            ])
            ->assertRedirect(route('account.conversations'));

        $conversation = $customer->conversations()->firstOrFail();
        $this->actingAs($otherCustomer)
            ->get(route('account.conversations.show', $conversation))
            ->assertNotFound();

        $this->actingAs($admin)
            ->post(route('admin.conversations.reply', $conversation), [
                'body' => 'Bonjour, nous revenons vers vous rapidement.',
            ])
            ->assertRedirect(route('admin.conversations.show', $conversation));
        $this->get(route('admin.conversations.index'))
            ->assertOk()
            ->assertSee('Question sur une commande');
        $this->get(route('admin.conversations.show', $conversation))
            ->assertOk()
            ->assertSee('Bonjour, pouvez-vous me renseigner ?');

        $this->actingAs($customer)
            ->get(route('account.conversations.show', $conversation))
            ->assertOk()
            ->assertSee('nous revenons vers vous rapidement.');
    }

    public function test_admin_can_assign_roles_but_cannot_remove_the_last_admin(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $customer = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee($customer->email);

        $this->patch(route('admin.users.update', $customer), ['role' => 'admin'])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'role' => 'admin',
        ]);

        $this->patch(route('admin.users.update', $admin), ['role' => 'client'])
            ->assertSessionHasErrors('role');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'role' => 'admin',
        ]);
    }
}
