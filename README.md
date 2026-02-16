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





# TradiPratic - Migrations, Modèles et Contrôleurs Laravel 10

Ce projet contient toutes les migrations, modèles Eloquent et contrôleurs pour l'application TradiPratic basée sur Laravel 10.

## Structure du projet

```
tradi_pratic/
├── migrations/          # Fichiers de migration de base de données
├── models/             # Modèles Eloquent
└── controllers/        # Contrôleurs HTTP
```

## Installation

### 1. Copier les migrations

Copiez tous les fichiers du dossier `migrations/` vers `database/migrations/` de votre projet Laravel :

```bash
cp migrations/*.php /chemin/vers/votre-projet-laravel/database/migrations/
```

### 2. Copier les modèles

Copiez tous les fichiers du dossier `models/` vers `app/Models/` de votre projet Laravel :

```bash
cp models/*.php /chemin/vers/votre-projet-laravel/app/Models/
```

**Note importante:** Les fichiers suivants contiennent plusieurs classes. Vous devrez les séparer en fichiers individuels :
- `PaymentRelations.php` contient : PaymentAppointment, PaymentProduct, PaymentHotelReservation, PaymentDonation
- `Donation.php` contient : Donor, Donation, DonationItem
- `Content.php` contient : Realisation, Recipe, Testimonial
- `Misc.php` contient : Contact, PubService, Bibliography, SocialLink, Setting, ActivityLog

### 3. Copier les contrôleurs

Copiez tous les fichiers du dossier `controllers/` vers `app/Http/Controllers/` :

```bash
cp controllers/*.php /chemin/vers/votre-projet-laravel/app/Http/Controllers/
```

**Note importante:** Le fichier `AdditionalControllers.php` contient plusieurs contrôleurs. Vous devrez les séparer en fichiers individuels :
- ContactController
- RecipeController
- RealisationController
- TestimonialController

### 4. Exécuter les migrations

Une fois les fichiers copiés, exécutez les migrations :

```bash
php artisan migrate
```

## Structure de la base de données

### Tables principales

1. **users** - Gestion des utilisateurs
2. **roles** - Rôles des utilisateurs
3. **permissions** - Permissions système
4. **appointments** - Rendez-vous de consultation
5. **hotels** - Hébergements disponibles
6. **rooms** - Chambres d'hôtel
7. **hotel_reservations** - Réservations d'hôtel
8. **products** - Produits en vente
9. **orders** - Commandes des clients
10. **order_items** - Détails des commandes
11. **donations** - Dons reçus
12. **payments** - Paiements effectués
13. **realisations** - Portfolio de réalisations
14. **recipes** - Recettes traditionnelles
15. **testimonials** - Témoignages clients
16. **contacts** - Messages de contact
17. **pub_services** - Services publicitaires

### Relations importantes

- Un **User** peut avoir plusieurs **Roles** (relation many-to-many)
- Un **Role** peut avoir plusieurs **Permissions** (relation many-to-many)
- Un **Appointment** peut avoir une **HotelReservation** (relation one-to-one)
- Un **Hotel** a plusieurs **Rooms** (relation one-to-many)
- Un **Order** appartient à un **User** et contient plusieurs **OrderItems**
- Un **Payment** peut être associé à des appointments, orders, reservations ou donations

## Fonctionnalités des contrôleurs

### AppointmentController
- Gestion complète des rendez-vous
- Confirmation et annulation de rendez-vous
- Génération de codes de rendez-vous

### OrderController
- Gestion des commandes
- Calcul automatique des totaux
- Gestion du stock des produits

### HotelReservationController
- Création de réservations d'hôtel
- Calcul automatique du coût total
- Gestion de la disponibilité des chambres

### PaymentController
- Traitement des paiements pour :
  - Rendez-vous
  - Commandes
  - Réservations d'hôtel
  - Dons
- Génération de transactions uniques

### DonationController
- Gestion des dons (argent, chèque, objets, colis)
- Suivi des articles donnés
- Statut de réception

## Routes suggérées

Ajoutez ces routes dans `routes/web.php` :

```php
// Users
Route::resource('users', UserController::class);

// Appointments
Route::resource('appointments', AppointmentController::class);
Route::post('appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
Route::post('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

// Hotels & Reservations
Route::resource('hotels', HotelController::class);
Route::resource('hotel-reservations', HotelReservationController::class);
Route::post('hotel-reservations/{hotelReservation}/confirm', [HotelReservationController::class, 'confirm'])->name('hotel-reservations.confirm');

// Products & Orders
Route::resource('products', ProductController::class);
Route::resource('orders', OrderController::class);
Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

// Donations
Route::resource('donations', DonationController::class);
Route::post('donations/{donation}/receive', [DonationController::class, 'receive'])->name('donations.receive');

// Payments
Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
Route::post('payments/appointment/{appointment}', [PaymentController::class, 'processAppointmentPayment'])->name('payments.appointment');
Route::post('payments/order/{order}', [PaymentController::class, 'processOrderPayment'])->name('payments.order');
Route::post('payments/hotel/{reservation}', [PaymentController::class, 'processHotelPayment'])->name('payments.hotel');
Route::post('payments/donation', [PaymentController::class, 'processDonationPayment'])->name('payments.donation');

// Content
Route::resource('realisations', RealisationController::class);
Route::resource('recipes', RecipeController::class);
Route::resource('testimonials', TestimonialController::class);
Route::post('testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');

// Contact
Route::resource('contacts', ContactController::class)->only(['index', 'store', 'show', 'destroy']);
Route::post('contacts/{contact}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
```

## Configuration supplémentaire

### 1. Stockage des fichiers

Assurez-vous de créer un lien symbolique pour le stockage public :

```bash
php artisan storage:link
```

### 2. Seeders (optionnel)

Créez des seeders pour les rôles et permissions de base :

```bash
php artisan make:seeder RoleSeeder
php artisan make:seeder PermissionSeeder
```

### 3. Policy (recommandé)

Créez des policies pour la gestion des autorisations :

```bash
php artisan make:policy AppointmentPolicy --model=Appointment
php artisan make:policy OrderPolicy --model=Order
```

## À faire

- [ ] Implémenter l'intégration avec une passerelle de paiement
- [ ] Ajouter l'envoi d'emails/SMS pour les notifications
- [ ] Créer les vues Blade pour chaque contrôleur
- [ ] Implémenter la validation des formulaires avec Form Requests
- [ ] Ajouter des tests unitaires et d'intégration
- [ ] Configurer les files d'attente pour les tâches longues
- [ ] Ajouter la gestion des fichiers uploadés
- [ ] Implémenter un système de cache pour les performances

## Support

Pour toute question ou problème, veuillez créer une issue dans le dépôt du projet.
