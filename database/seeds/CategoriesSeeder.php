<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Sneefr\Models\Category;

class CategoriesSeeder extends Seeder
{

    public function run()
    {
        Category::create(['id' => 1, 'child_of' => null, 'name' => 'Véhicules']);
            Category::create(['id' => 2, 'child_of' => 1, 'name' => 'Voiture']);
            Category::create(['id' => 3, 'child_of' => 1, 'name' => 'Moto']);
            Category::create(['id' => 4, 'child_of' => 1, 'name' => 'Caravaning']);
            Category::create(['id' => 5, 'child_of' => 1, 'name' => 'Utilitaires']);
            Category::create(['id' => 6, 'child_of' => 1, 'name' => 'Nautisme']);
            Category::create(['id' => 7, 'child_of' => 1, 'name' => 'Equipement auto/moto']);
            Category::create(['id' => 70, 'child_of' => 1, 'name' => 'Autres']);

        Category::create(['id' => 8, 'child_of' => null, 'name' => 'Immobilier']);
            Category::create(['id' => 9, 'child_of' => 8, 'name' => 'Ventes immobilière']);
            Category::create(['id' => 10, 'child_of' => 8, 'name' => 'Locations']);
            Category::create(['id' => 11, 'child_of' => 8, 'name' => 'Colocations']);
            Category::create(['id' => 12, 'child_of' => 8, 'name' => 'Locations de vacances']);
            Category::create(['id' => 13, 'child_of' => 8, 'name' => 'Bureaux & Commerces']);
            Category::create(['id' => 71, 'child_of' => 8, 'name' => 'Autres']);

        Category::create(['id' => 14, 'child_of' => null, 'name' => 'Multimedia']);
            Category::create(['id' => 15, 'child_of' => 14, 'name' => 'Informatique']);
            Category::create(['id' => 16, 'child_of' => 14, 'name' => 'Consoles & Jeux Vidéo']);
            Category::create(['id' => 17, 'child_of' => 14, 'name' => 'Image et Son']);
            Category::create(['id' => 18, 'child_of' => 14, 'name' => 'Téléphonie']);
            Category::create(['id' => 72, 'child_of' => 14, 'name' => 'Autres']);

        Category::create(['id' => 19, 'child_of' => null, 'name' => 'Maison & Déco']);
            Category::create(['id' => 20, 'child_of' => 19, 'name' => 'Ameublement']);
            Category::create(['id' => 21, 'child_of' => 19, 'name' => 'Electroménager']);
            Category::create(['id' => 22, 'child_of' => 19, 'name' => 'Décoration & Linge de maison']);
            Category::create(['id' => 23, 'child_of' => 19, 'name' => 'Bricolage & Jardinage']);
            Category::create(['id' => 24, 'child_of' => 19, 'name' => 'Bagagerie']);
            Category::create(['id' => 73, 'child_of' => 19, 'name' => 'Autres']);

        Category::create(['id' => 25, 'child_of' => null, 'name' => 'Mode']);
            Category::create(['id' => 26, 'child_of' => 25, 'name' => 'Vêtements Femme']);
            Category::create(['id' => 27, 'child_of' => 25, 'name' => 'Vêtements Homme']);
            Category::create(['id' => 28, 'child_of' => 25, 'name' => 'Chaussures Femme']);
            Category::create(['id' => 29, 'child_of' => 25, 'name' => 'Chaussures Homme']);
            Category::create(['id' => 30, 'child_of' => 25, 'name' => 'Accessoires']);
            Category::create(['id' => 59, 'child_of' => 25, 'name' => 'Enfants/bébé']);
            Category::create(['id' => 74, 'child_of' => 25, 'name' => 'Autres']);

        Category::create(['id' => 31, 'child_of' => null, 'name' => 'Bijoux']);
            Category::create(['id' => 60, 'child_of' => 31, 'name' => 'Montres']);
            Category::create(['id' => 61, 'child_of' => 31, 'name' => 'Bijoux femme']);
            Category::create(['id' => 62, 'child_of' => 31, 'name' => 'Bijoux homme']);
            Category::create(['id' => 65, 'child_of' => 31, 'name' => 'Autres']);

        Category::create(['id' => 32, 'child_of' => null, 'name' => 'Beauté']);
            Category::create(['id' => 63, 'child_of' => 32, 'name' => 'Cosmétiques']);
            Category::create(['id' => 64, 'child_of' => 32, 'name' => 'Autres']);

        Category::create(['id' => 33, 'child_of' => null, 'name' => 'Emplois']);
            Category::create(['id' => 34, 'child_of' => 33, 'name' => 'Emplois']);
            Category::create(['id' => 35, 'child_of' => 33, 'name' => 'Services']);
            Category::create(['id' => 36, 'child_of' => 33, 'name' => 'Evennement']);
            Category::create(['id' => 37, 'child_of' => 33, 'name' => 'Co-voiturage']);
            Category::create(['id' => 75, 'child_of' => 33, 'name' => 'Autres']);

        Category::create(['id' => 38, 'child_of' => null, 'name' => 'Services']);
            Category::create(['id' => 66, 'child_of' => 38, 'name' => 'Services']);

        Category::create(['id' => 39, 'child_of' => null, 'name' => 'Co-Voiturage']);
            Category::create(['id' => 67, 'child_of' => 39, 'name' => 'Co-Voiturage']);

        Category::create(['id' => 40, 'child_of' => null, 'name' => 'Loisirs']);
            Category::create(['id' => 41, 'child_of' => 40, 'name' => 'CD/DVD']);
            Category::create(['id' => 42, 'child_of' => 40, 'name' => 'Livres']);
            Category::create(['id' => 43, 'child_of' => 40, 'name' => 'Sports & Hobbies']);
            Category::create(['id' => 44, 'child_of' => 40, 'name' => 'Musique']);
            Category::create(['id' => 45, 'child_of' => 40, 'name' => 'Jeux/jouets']);
            Category::create(['id' => 76, 'child_of' => 40, 'name' => 'Autres']);

        Category::create(['id' => 47, 'child_of' => null, 'name' => 'Matériel professionnel']);
            Category::create(['id' => 48, 'child_of' => 47, 'name' => 'Matériel agricole']);
            Category::create(['id' => 49, 'child_of' => 47, 'name' => 'Transport manutention']);
            Category::create(['id' => 50, 'child_of' => 47, 'name' => 'BTP']);
            Category::create(['id' => 51, 'child_of' => 47, 'name' => 'Outillage']);
            Category::create(['id' => 52, 'child_of' => 47, 'name' => 'Restauration/hôtellerie']);
            Category::create(['id' => 53, 'child_of' => 47, 'name' => 'Fournitures de bureau']);
            Category::create(['id' => 54, 'child_of' => 47, 'name' => 'Commerces']);
            Category::create(['id' => 55, 'child_of' => 47, 'name' => 'Matériel médical']);
            Category::create(['id' => 77, 'child_of' => 47, 'name' => 'Autres']);

        Category::create(['id' => 56, 'child_of' => null, 'name' => 'Gastronomie']);
            Category::create(['id' => 68, 'child_of' => 56, 'name' => 'Gastronomie']);

        Category::create(['id' => 57, 'child_of' => null, 'name' => 'Art & Culture']);
            Category::create(['id' => 69, 'child_of' => 57, 'name' => 'Art & Culture']);

        Category::create(['id' => 58, 'child_of' => null, 'name' => 'Autre']);
            Category::create(['id' => 78, 'child_of' => 58, 'name' => 'Autres']);

    }

}
