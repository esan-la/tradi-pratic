<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            [
                'title' => 'Riz gras au poulet',
                'description' => 'Le riz gras est un plat convivial et savoureux...',
                'ingredients' => [
                    '500g de riz',
                    '1 poulet entier découpé',
                    '3 tomates mûres',
                    '2 oignons',
                    '1 boîte de concentré de tomate',
                    '3 gousses d\'ail',
                    '1 cube Maggi',
                    'Huile végétale',
                    'Sel et poivre',
                    'Piment (selon goût)',
                    '1L d\'eau',
                ],
                'instructions' => [
                    'Laver et découper le poulet...',
                    'Faire revenir les oignons...',
                    'Ajouter les tomates...',
                ],
                'prep_time' => 20,
                'cook_time' => 50,
                'servings' => 6,
                'category' => 'plats',
                'image' => 'recipes/riz-gras.jpg',
                'video_url' => 'https://www.youtube.com/watch?v=example1',
                'is_published' => true,
            ],

            [
                'title' => 'Tô sauce gombo',
                'description' => 'Le plat national du Burkina Faso...',
                'ingredients' => [
                    '500g de farine de mil',
                    '300g de gombo frais',
                    '2 tomates',
                    '1 oignon',
                    '100g de poisson fumé',
                    '2 cuillères de soumbala',
                    'Sel',
                    'Piment',
                    '1,5L d\'eau',
                ],
                'instructions' => [
                    'Porter 1L d\'eau à ébullition...',
                ],
                'prep_time' => 15,
                'cook_time' => 35,
                'servings' => 4,
                'category' => 'plats',
                'image' => 'recipes/to-gombo.jpg',
                'video_url' => null,
                'is_published' => true,
            ],

            [
                'title' => 'Riz au gras de poulet bicyclette',
                'description' => 'Une variante savoureuse du riz gras...',
                'ingredients' => [
                    '500g de riz',
                    '1 poulet bicyclette',
                    '3 tomates',
                    '2 oignons',
                ],
                'instructions' => [
                    'Mariner le poulet...',
                ],
                'prep_time' => 25,
                'cook_time' => 45,
                'servings' => 6,
                'category' => 'plats',
                'image' => 'recipes/riz-poulet-bicyclette.jpg',
                'video_url' => 'https://www.youtube.com/watch?v=example2',
                'is_published' => true,
            ],

            [
                'title' => 'Babenda (couscous aux feuilles de baobab)',
                'description' => 'Plat traditionnel à base de couscous...',
                'ingredients' => [
                    '500g de couscous de mil',
                    '200g de feuilles de baobab',
                ],
                'instructions' => [
                    'Faire tremper les feuilles...',
                ],
                'prep_time' => 40,
                'cook_time' => 45,
                'servings' => 5,
                'category' => 'plats',
                'image' => 'recipes/babenda.jpg',
                'video_url' => null,
                'is_published' => true,
            ],

            [
                'title' => 'Poulet DG (Directeur Général)',
                'description' => 'Un plat festif et généreux...',
                'ingredients' => [
                    '1 poulet',
                    '3 plantains',
                ],
                'instructions' => [
                    'Mariner le poulet...',
                ],
                'prep_time' => 30,
                'cook_time' => 40,
                'servings' => 6,
                'category' => 'plats',
                'image' => 'recipes/poulet-dg.jpg',
                'video_url' => null,
                'is_published' => true,
            ],

            [
                'title' => 'Sauce d\'arachide',
                'description' => 'Une sauce crémeuse et riche...',
                'ingredients' => [
                    '300g de pâte d\'arachide',
                    '500g de viande',
                ],
                'instructions' => [
                    'Couper la viande...',
                ],
                'prep_time' => 20,
                'cook_time' => 40,
                'servings' => 5,
                'category' => 'plats',
                'image' => 'recipes/sauce-arachide.jpg',
                'video_url' => 'https://www.youtube.com/watch?v=example3',
                'is_published' => true,
            ],

            [
                'title' => 'Ragoût de mouton',
                'description' => 'Un ragoût savoureux et réconfortant...',
                'ingredients' => [
                    '1kg de viande de mouton',
                ],
                'instructions' => [
                    'Découper la viande...',
                ],
                'prep_time' => 25,
                'cook_time' => 75,
                'servings' => 6,
                'category' => 'plats',
                'image' => 'recipes/ragout-mouton.jpg',
                'video_url' => null,
                'is_published' => true,
            ],

            [
                'title' => 'Zoom-koom (boisson de mil)',
                'description' => 'Boisson traditionnelle rafraîchissante...',
                'ingredients' => [
                    '500g de farine de mil',
                ],
                'instructions' => [
                    'Délayer la farine...',
                ],
                'prep_time' => 15,
                'cook_time' => 0,
                'servings' => 8,
                'category' => 'boissons',
                'image' => 'recipes/zoom-koom.jpg',
                'video_url' => null,
                'is_published' => true,
            ],
        ];

        foreach ($recipes as $recipe) {
            Recipe::create($recipe);
        }

        $this->command->info('✅ ' . count($recipes) . ' recettes créées avec succès!');
    }
}
