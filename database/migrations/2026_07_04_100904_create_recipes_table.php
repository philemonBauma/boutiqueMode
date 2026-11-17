<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('ingredients');
            $table->text('instructions');
            $table->timestamps();
        });

        $recipes = [
        1 => [
            'title' => 'Spaghetti Bolognese',
            'description' => 'A classic Italian pasta dish with rich meat sauce.',
            'ingredients' => [
                'spaghetti',
                'ground beef',
                'onion',
                'garlic',
                'tomato sauce',
                'olive oil',
                'salt',
                'pepper'
            ],
            'instructions' => [
                'Cook spaghetti according to package instructions.',
                'In a separate pan, sauté onion and garlic in olive oil until translucent.',
                'Add ground beef and cook until browned.',
                'Pour in tomato sauce and simmer for 20 minutes.',
                'Season with salt and pepper to taste.',
                'Serve sauce over cooked spaghetti.'
            ]
        ],
        2 => [
            'title' => 'Chicken Curry',
            'description' => 'A flavorful and spicy chicken curry dish.',
            'ingredients' => [
                'chicken pieces',
                'onion',
                'garlic',
                'ginger',
                'curry powder',
                'coconut milk',
                'vegetable oil',
                'salt',
                'pepper'
            ],
            'instructions' => [
                'Heat oil in a pan and sauté onion, garlic, and ginger until fragrant.',
                'Add chicken pieces and cook until browned.',
                'Stir in curry powder and cook for 1-2 minutes.',
                'Pour in coconut milk and simmer until chicken is cooked through.',
                'Season with salt and pepper to taste.',
                'Serve hot with rice or naan bread.'
            ]
        ],
        3 => [
            'title' => 'Vegetable Stir-Fry',
            'description' => 'A quick and healthy vegetable stir-fry.',
            'ingredients' => [
                'broccoli',
                'carrots',
                'bell peppers',
                'snap peas',
                'soy sauce',
                'garlic',
                'ginger',
                'sesame oil'
            ],
            'instructions' => [
                'Heat sesame oil in a wok or large pan.',
                'Add garlic and ginger, sauté for 1 minute.',
                'Add vegetables and stir-fry for 5-7 minutes until tender-crisp.',
                'Pour in soy sauce and toss to coat.',
                'Serve hot over rice or noodles.'
            ]
        ],
        4 => [
            'title' => 'Caesar Salad',
            'description' => 'A refreshing and classic Caesar salad.',
            'ingredients' => [
                'romaine lettuce',
                'croutons',
                'parmesan cheese',
                'caesar dressing'
            ],
            'instructions' => [
                'Wash and chop the romaine lettuce.',
                'In a large bowl, toss the lettuce with caesar dressing.',
                'Add croutons and parmesan cheese.',
                'Toss gently to combine.'
            ]
        ]
    ];

    foreach ($recipes as $recipe) {
        DB::table('recipes')->insert([
            'title' => $recipe['title'],
            'ingredients' => json_encode($recipe['ingredients']),
            'instructions' => json_encode($recipe['instructions'])
        ]);

    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
