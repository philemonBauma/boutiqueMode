<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Chemise en lin',
                'slug' => 'chemise-en-lin',
                'category' => 'Chemises',
                'gender' => 'homme',
                'description' => 'Une chemise légère en lin naturel, parfaite pour les journées ensoleillées.',
                'price' => 59.00,
                'compare_at_price' => 75.00,
                'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=85',
                'is_featured' => true,
            ],
            [
                'name' => 'Veste de ville',
                'slug' => 'veste-de-ville',
                'category' => 'Vestes',
                'gender' => 'homme',
                'description' => 'Une coupe structurée et confortable, pensée pour passer du bureau au week-end.',
                'price' => 129.00,
                'compare_at_price' => null,
                'image' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=800&q=85',
                'is_featured' => true,
            ],
            [
                'name' => 'Pull col rond',
                'slug' => 'pull-col-rond',
                'category' => 'Maille',
                'gender' => 'homme',
                'description' => 'Un essentiel doux et facile à associer, tricoté dans une maille agréable.',
                'price' => 79.00,
                'compare_at_price' => null,
                'image' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=85',
                'is_featured' => false,
            ],
            [
                'name' => 'Robe fluide',
                'slug' => 'robe-fluide',
                'category' => 'Robes',
                'gender' => 'femme',
                'description' => 'Une silhouette aérienne et élégante à porter en journée comme en soirée.',
                'price' => 89.00,
                'compare_at_price' => null,
                'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=800&q=85',
                'is_featured' => true,
            ],
            [
                'name' => 'Blazer essentiel',
                'slug' => 'blazer-essentiel',
                'category' => 'Vestes',
                'gender' => 'femme',
                'description' => 'Un blazer intemporel à la ligne moderne, pour une allure naturellement assurée.',
                'price' => 119.00,
                'compare_at_price' => 145.00,
                'image' => 'https://images.unsplash.com/photo-1591369822096-ffd140ec948f?auto=format&fit=crop&w=800&q=85',
                'is_featured' => true,
            ],
            [
                'name' => 'Chemisier en coton',
                'slug' => 'chemisier-en-coton',
                'category' => 'Chemises',
                'gender' => 'femme',
                'description' => 'Un chemisier en coton doux aux détails délicats pour tous les jours.',
                'price' => 64.00,
                'compare_at_price' => null,
                'image' => 'https://images.unsplash.com/photo-1598554747436-c9293d6a588f?auto=format&fit=crop&w=800&q=85',
                'is_featured' => false,
            ],
            [
                'name' => 'Sac signature',
                'slug' => 'sac-signature',
                'category' => 'Accessoires',
                'gender' => 'femme',
                'description' => 'Le petit sac pratique et raffiné qui accompagne vos essentiels.',
                'price' => 74.00,
                'compare_at_price' => null,
                'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=85',
                'is_featured' => false,
            ],
            [
                'name' => 'Jean coupe droite',
                'slug' => 'jean-coupe-droite',
                'category' => 'Pantalons',
                'gender' => 'homme',
                'description' => 'Le jean facile à vivre, avec une coupe droite et un denim confortable.',
                'price' => 89.00,
                'compare_at_price' => null,
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=85',
                'is_featured' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }
    }
}
