<?php

namespace Database\Seeders;

use App\Models\Realisation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $realisations = [
            // Agriculture
            [
                'title' => 'Culture de tomates biologiques',
                'description' => 'Mise en place d\'une exploitation moderne de tomates biologiques sur 2 hectares. Ce projet a permis d\'augmenter les rendements de 40% grâce à des techniques d\'irrigation goutte-à-goutte et l\'utilisation d\'engrais naturels. Les tomates sont cultivées sans pesticides chimiques, garantissant une production saine et respectueuse de l\'environnement.',
                'category' => 'Agriculture',
                'image' => 'realisations/tomates-bio.jpg',
                'gallery' => [
                    'realisations/gallery/tomates-1.jpg',
                    'realisations/gallery/tomates-2.jpg',
                    'realisations/gallery/tomates-3.jpg',
                ],
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'is_featured' => true,
                'is_published' => true,
                'order' => 1,
            ],
            [
                'title' => 'Plantation de moringa',
                'description' => 'Développement d\'une plantation de moringa de 5 hectares. Le moringa, appelé "arbre de vie", est cultivé pour ses feuilles nutritives et ses graines. Ce projet contribue à la sécurité alimentaire locale et génère des revenus pour les familles paysannes.',
                'category' => 'Agriculture',
                'image' => 'realisations/moringa.jpg',
                'gallery' => [
                    'realisations/gallery/moringa-1.jpg',
                    'realisations/gallery/moringa-2.jpg',
                ],
                'video_url' => null,
                'is_featured' => false,
                'is_published' => true,
                'order' => 2,
            ],
            [
                'title' => 'Maraîchage intensif',
                'description' => 'Installation d\'un système de maraîchage intensif avec rotation des cultures. Production de légumes variés : choux, carottes, oignons, aubergines et piments. Utilisation de techniques modernes combinées au savoir-faire traditionnel.',
                'category' => 'Agriculture',
                'image' => 'realisations/maraichage.jpg',
                'gallery' => null,
                'video_url' => null,
                'is_featured' => true,
                'is_published' => true,
                'order' => 3,
            ],

            // Élevage
            [
                'title' => 'Élevage moderne de poulets locaux',
                'description' => 'Création d\'un poulailler moderne de 500 poulets de race locale. Le projet intègre des techniques d\'alimentation améliorées, un suivi sanitaire rigoureux et des infrastructures adaptées au climat burkinabé. Production d\'œufs et de viande de qualité pour le marché local.',
                'category' => 'Élevage',
                'image' => 'realisations/poulets.jpg',
                'gallery' => [
                    'realisations/gallery/poulets-1.jpg',
                    'realisations/gallery/poulets-2.jpg',
                    'realisations/gallery/poulets-3.jpg',
                    'realisations/gallery/poulets-4.jpg',
                ],
                'video_url' => 'https://www.youtube.com/watch?v=example2',
                'is_featured' => true,
                'is_published' => true,
                'order' => 4,
            ],
            [
                'title' => 'Ferme d\'élevage de moutons',
                'description' => 'Développement d\'une ferme d\'élevage de moutons Djallonké, une race locale résistante aux maladies tropicales. Le projet inclut la construction d\'abris adaptés, la mise en place de pâturages améliorés et un programme de reproduction sélective.',
                'category' => 'Élevage',
                'image' => 'realisations/moutons.jpg',
                'gallery' => [
                    'realisations/gallery/moutons-1.jpg',
                    'realisations/gallery/moutons-2.jpg',
                ],
                'video_url' => null,
                'is_featured' => false,
                'is_published' => true,
                'order' => 5,
            ],
            [
                'title' => 'Apiculture traditionnelle améliorée',
                'description' => 'Installation de 50 ruches modernes pour la production de miel naturel. Le projet combine les techniques traditionnelles d\'apiculture avec des ruches à cadres mobiles pour améliorer les rendements tout en préservant les abeilles locales.',
                'category' => 'Élevage',
                'image' => 'realisations/apiculture.jpg',
                'gallery' => [
                    'realisations/gallery/apiculture-1.jpg',
                    'realisations/gallery/apiculture-2.jpg',
                    'realisations/gallery/apiculture-3.jpg',
                ],
                'video_url' => null,
                'is_featured' => false,
                'is_published' => true,
                'order' => 6,
            ],

            // Artisanat
            [
                'title' => 'Tissage traditionnel Faso Dan Fani',
                'description' => 'Préservation et promotion du tissage traditionnel burkinabé. Formation de 20 artisans aux techniques ancestrales du Faso Dan Fani, le tissu traditionnel du Burkina Faso. Production de pagnes, écharpes et autres articles textiles authentiques.',
                'category' => 'Artisanat',
                'image' => 'realisations/tissage.jpg',
                'gallery' => [
                    'realisations/gallery/tissage-1.jpg',
                    'realisations/gallery/tissage-2.jpg',
                    'realisations/gallery/tissage-3.jpg',
                ],
                'video_url' => 'https://www.youtube.com/watch?v=example3',
                'is_featured' => true,
                'is_published' => true,
                'order' => 7,
            ],
            [
                'title' => 'Poterie traditionnelle',
                'description' => 'Atelier de poterie utilisant des techniques ancestrales transmises de génération en génération. Production de canaris, jarres et objets décoratifs en argile locale. Un art qui allie utilité et esthétique.',
                'category' => 'Artisanat',
                'image' => 'realisations/poterie.jpg',
                'gallery' => [
                    'realisations/gallery/poterie-1.jpg',
                    'realisations/gallery/poterie-2.jpg',
                ],
                'video_url' => null,
                'is_featured' => false,
                'is_published' => true,
                'order' => 8,
            ],
            [
                'title' => 'Vannerie et tressage',
                'description' => 'Fabrication artisanale de paniers, nattes et chapeaux en fibres végétales. Valorisation des savoir-faire locaux et création d\'emplois pour les femmes rurales. Production écologique et durable.',
                'category' => 'Artisanat',
                'image' => 'realisations/vannerie.jpg',
                'gallery' => null,
                'video_url' => null,
                'is_featured' => false,
                'is_published' => true,
                'order' => 9,
            ],

            // Autres
            [
                'title' => 'Transformation de produits locaux',
                'description' => 'Unité de transformation de céréales locales (mil, sorgho, maïs) en farine enrichie. Installation d\'équipements modernes pour la production de produits alimentaires de qualité tout en préservant les valeurs nutritionnelles.',
                'category' => 'Autres',
                'image' => 'realisations/transformation.jpg',
                'gallery' => [
                    'realisations/gallery/transformation-1.jpg',
                    'realisations/gallery/transformation-2.jpg',
                ],
                'video_url' => null,
                'is_featured' => false,
                'is_published' => true,
                'order' => 10,
            ],
            [
                'title' => 'Jardin médicinal',
                'description' => 'Création d\'un jardin de plantes médicinales traditionnelles. Culture et préservation de plus de 30 espèces de plantes utilisées dans la médecine traditionnelle burkinabé. Un patrimoine végétal à transmettre aux générations futures.',
                'category' => 'Autres',
                'image' => 'realisations/jardin-medicinal.jpg',
                'gallery' => [
                    'realisations/gallery/medicinal-1.jpg',
                    'realisations/gallery/medicinal-2.jpg',
                    'realisations/gallery/medicinal-3.jpg',
                ],
                'video_url' => null,
                'is_featured' => true,
                'is_published' => true,
                'order' => 11,
            ],
        ];

        foreach ($realisations as $realisation) {
            Realisation::create($realisation);
        }

        $this->command->info('✅ ' . count($realisations) . ' réalisations créées avec succès!');
    }
}
