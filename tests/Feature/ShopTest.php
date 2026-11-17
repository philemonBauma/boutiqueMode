<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ProductSeeder::class);
    }

    public function test_shop_displays_products_and_filters_by_gender(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Chemise en lin')
            ->assertSee('Robe fluide');

        $this->get('/shop?gender=femme')
            ->assertOk()
            ->assertSee('Robe fluide')
            ->assertDontSee('Chemise en lin');
    }

    public function test_customer_can_add_and_remove_a_product_from_the_cart(): void
    {
        $product = Product::where('slug', 'robe-fluide')->firstOrFail();

        $this->post(route('cart.add', $product))
            ->assertRedirect();

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Robe fluide')
            ->assertSee('89,00 €');

        $this->delete(route('cart.remove', $product))
            ->assertRedirect(route('cart.index'));

        $this->get(route('cart.index'))->assertSee('Votre panier attend ses premières pièces.');
    }
}
