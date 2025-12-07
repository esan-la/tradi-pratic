## ** Notes sur les Seeders **
# Exécuter tous les seeders
php artisan db:seed

# Ou tout réinitialiser et seeder
php artisan migrate:fresh --seed

# Exécuter un seeder spécifique
php artisan db:seed --class=RealisationSeeder
php artisan db:seed --class=RecipeSeeder
php artisan db:seed --class=AppointmentSeeder


## ⚠️ **Note importante sur les images**

Les seeders font référence à des chemins d'images. Vous devrez:

1. Créer les dossiers dans `storage/app/public/`:
```bash
mkdir -p storage/app/public/realisations
mkdir -p storage/app/public/realisations/gallery
mkdir -p storage/app/public/recipes
```

2. Créer le lien symbolique:
```bash
php artisan storage:link
```

3. Ajouter des images par défaut ou modifier les seeders pour utiliser `null` si pas d'images disponibles.

Tous les seeders sont maintenant prêts à l'emploi! 🎉
