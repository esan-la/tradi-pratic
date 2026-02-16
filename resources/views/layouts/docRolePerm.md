# Hiérarchie des Rôles et Permissions - TradiPratic

## 📊 Vue d'ensemble

Le système TradiPratic utilise une hiérarchie de rôles avec 5 niveaux d'accès. Chaque rôle a des permissions spécifiques basées sur ses responsabilités.

## 🎭 Rôles et Responsabilités

### 1. 👑 Super Admin (super_admin)
**Description** : Contrôle total du système

**Accès** : TOUTES les permissions (76 permissions)

**Responsabilités** :
- Gestion complète des utilisateurs et rôles
- Configuration système
- Suppression de données critiques
- Effacement des logs
- Supervision générale

**Identifiants par défaut** :
- Email: superadmin@tradipratic.com
- Mot de passe: SuperAdmin@2024

---

### 2. 🛡️ Administrateur (admin)
**Description** : Gestion complète sauf actions critiques

**Accès** : Toutes les permissions SAUF :
- `users.delete` - Ne peut pas supprimer d'utilisateurs
- `roles.delete` - Ne peut pas supprimer de rôles
- `logs.clear` - Ne peut pas effacer les logs

**Responsabilités** :
- Gestion des utilisateurs (créer, modifier, visualiser)
- Gestion des rôles (créer, modifier, visualiser)
- Toutes les permissions du Manager
- Configuration des paramètres
- Consultation des logs

**Identifiants par défaut** :
- Email: admin@tradipratic.com
- Mot de passe: Admin@2024

---

### 3. 💼 Manager (manager)
**Description** : Gestion complète du contenu de la plateforme

**Permissions (63 permissions)** :

#### 📅 Rendez-vous
- ✅ appointments.view
- ✅ appointments.create
- ✅ appointments.edit
- ✅ appointments.delete
- ✅ appointments.update-status

#### 🎨 Réalisations
- ✅ realisations.view
- ✅ realisations.create
- ✅ realisations.edit
- ✅ realisations.delete
- ✅ realisations.publish

#### 🍽️ Recettes
- ✅ recipes.view
- ✅ recipes.create
- ✅ recipes.edit
- ✅ recipes.delete
- ✅ recipes.publish

#### 💬 Témoignages
- ✅ testimonials.view
- ✅ testimonials.approve
- ✅ testimonials.delete

#### 📢 Publicité de Services
- ✅ pub-services.view
- ✅ pub-services.create
- ✅ pub-services.edit
- ✅ pub-services.delete
- ✅ pub-services.publish
- ✅ pub-services.approve

#### 📖 Bibliographie
- ✅ bibliography.view
- ✅ bibliography.edit

#### 🏨 Hôtels & Réservations
- ✅ hotels.* (toutes les permissions hôtels)
- ✅ reservations.* (toutes les permissions réservations)

#### 🛍️ Produits & Commandes
- ✅ products.* (toutes les permissions produits)
- ✅ orders.* (toutes les permissions commandes)

#### 💝 Dons
- ✅ donations.* (toutes les permissions dons)

#### 📧 Contacts
- ✅ contacts.* (toutes les permissions contacts)

#### 💳 Paiements
- ✅ payments.view (vue uniquement)

**Identifiants par défaut** :
- Email: manager@tradipratic.com
- Mot de passe: Manager@2024

---

### 4. 🏨 Réceptionniste (receptionist)
**Description** : Gestion des hôtels et réservations

**Permissions (13 permissions)** :

#### 🏨 Hôtels
- ✅ hotels.view
- ✅ hotels.create
- ✅ hotels.edit
- ✅ hotels.delete

#### 🛏️ Réservations
- ✅ reservations.view
- ✅ reservations.create
- ✅ reservations.edit
- ✅ reservations.delete
- ✅ reservations.confirm
- ✅ reservations.cancel

#### 💳 Paiements
- ✅ payments.view (vue uniquement)

**Identifiants par défaut** :
- Email: receptionist@tradipratic.com
- Mot de passe: Reception@2024

---

### 5. 📞 Service Client (customer_service)
**Description** : Gestion des messages de contact

**Permissions (3 permissions)** :

#### 📧 Contacts
- ✅ contacts.view
- ✅ contacts.reply
- ✅ contacts.delete

**Identifiants par défaut** :
- Email: support@tradipratic.com
- Mot de passe: Support@2024

---

## 📋 Liste Complète des Permissions (70)

### 👥 Utilisateurs (4)
- users.view
- users.create
- users.edit
- users.delete

### 🔐 Rôles (4)
- roles.view
- roles.create
- roles.edit
- roles.delete

### 📅 Rendez-vous (5)
- appointments.view
- appointments.create
- appointments.edit
- appointments.delete
- appointments.update-status

### 🏨 Hôtels (4)
- hotels.view
- hotels.create
- hotels.edit
- hotels.delete

### 🛏️ Réservations (6)
- reservations.view
- reservations.create
- reservations.edit
- reservations.delete
- reservations.confirm
- reservations.cancel

### 📦 Produits (4)
- products.view
- products.create
- products.edit
- products.delete

### 🛒 Commandes (5)
- orders.view
- orders.create
- orders.edit
- orders.delete
- orders.update-status

### 💝 Dons (5)
- donations.view
- donations.create
- donations.edit
- donations.delete
- donations.receive

### 💳 Paiements (2)
- payments.view
- payments.process

### 📧 Contacts (3)
- contacts.view
- contacts.reply
- contacts.delete

### 🎨 Réalisations (5)
- realisations.view
- realisations.create
- realisations.edit
- realisations.delete
- realisations.publish

### 🍽️ Recettes (5)
- recipes.view
- recipes.create
- recipes.edit
- recipes.delete
- recipes.publish

### 💬 Témoignages (3)
- testimonials.view
- testimonials.approve
- testimonials.delete

### 📖 Bibliographie (2)
- bibliography.view
- bibliography.edit

### ⚙️ Paramètres (2)
- settings.view
- settings.edit

### 📊 Logs (2)
- logs.view
- logs.clear

---

## 🌐 Accès Public (Sans Authentification)

### Fonctionnalités publiques accessibles sans compte :

#### ✅ Envoi de messages via formulaire de contact
```php
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
```
- Permet à tout visiteur d'envoyer un message
- Les messages sont visibles par le Manager, Admin et Super Admin
- Customer Service peut y répondre

#### ✅ Faire un don
```php
Route::post('/donate', [DonationController::class, 'publicStore'])->name('donate.store');
```
- Permet à tout visiteur de faire un don
- Création automatique d'un donateur s'il n'existe pas
- Option anonymat disponible
- Les dons sont visibles par le Manager, Admin et Super Admin

#### ✅ Prendre un rendez-vous
```php
Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');
```
- Permet à tout visiteur de prendre rendez-vous
- Pas besoin de compte utilisateur
- Les rendez-vous sont visibles par le Manager, Admin et Super Admin

---

## 🔒 Sécurité

### Protection des routes
```php
// Route protégée par authentification
Route::middleware('auth')->group(function () {
    // Routes admin
});

// Route protégée par permission
Route::middleware(['auth', 'permission:users.view'])->group(function () {
    // Routes nécessitant la permission users.view
});

// Route protégée par rôle
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    // Routes nécessitant le rôle admin OU super_admin
});
```

### Vérification dans les vues
```blade
@if(Auth::user()->hasPermission('products.create'))
    <button>Créer un produit</button>
@endif

@if(Auth::user()->hasRole('admin'))
    <a href="{{ route('admin.users.index') }}">Gérer les utilisateurs</a>
@endif
```

### Vérification dans les contrôleurs
```php
public function destroy(User $user)
{
    if (!auth()->user()->hasPermission('users.delete')) {
        abort(403, 'Vous n\'avez pas la permission de supprimer des utilisateurs.');
    }
    
    $user->delete();
    return redirect()->back()->with('success', 'Utilisateur supprimé.');
}
```

---

## 📊 Tableau Comparatif

| Fonctionnalité | Super Admin | Admin | Manager | Receptionist | Customer Service |
|----------------|-------------|-------|---------|--------------|------------------|
| **Gestion Utilisateurs** | ✅ Complet | ✅ Sauf suppression | ❌ | ❌ | ❌ |
| **Gestion Rôles** | ✅ Complet | ✅ Sauf suppression | ❌ | ❌ | ❌ |
| **Rendez-vous** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Réalisations** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Recettes** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Témoignages** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Bibliographie** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Hôtels** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Réservations** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Produits** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Commandes** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Dons** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Contacts** | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Paiements (vue)** | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Paiements (traiter)** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Paramètres** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Logs (vue)** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Logs (effacer)** | ✅ | ❌ | ❌ | ❌ | ❌ |

---

## 🔄 Modifier les Permissions

### Ajouter une nouvelle permission
```bash
# 1. Ajouter dans PermissionSeeder.php
['name' => 'ma-nouvelle.permission', 'description' => 'Description'],

# 2. Re-seed
php artisan db:seed --class=PermissionSeeder

# 3. Assigner au rôle
php artisan db:seed --class=RoleSeeder
```

### Changer le rôle d'un utilisateur
```php
// Via le code
$user = User::find(1);
$user->roles()->sync([2]); // ID du rôle

// Via interface admin
// Aller dans Utilisateurs > Modifier > Changer le rôle
```

---

## ⚠️ Notes Importantes

1. **Super Admin** : Un seul par défaut, à protéger absolument
2. **Admin** : Pour les administrateurs de confiance
3. **Manager** : Pour la gestion quotidienne du contenu
4. **Réceptionniste** : Rôle spécialisé pour les réservations
5. **Customer Service** : Rôle limité au support client

6. **Accès public** : Contact, Dons et Rendez-vous sont accessibles sans compte
7. **Les visiteurs** peuvent interagir avec la plateforme sans créer de compte
8. **Les paiements publics** sont enregistrés avec le statut "guest"

---

## 🔐 Bonnes Pratiques

1. ✅ Changez TOUS les mots de passe par défaut en production
2. ✅ N'accordez que les permissions nécessaires
3. ✅ Créez des rôles personnalisés si besoin
4. ✅ Auditez régulièrement les accès via les logs
5. ✅ Limitez le nombre de Super Admins
6. ✅ Utilisez l'authentification à deux facteurs (à implémenter)
7. ✅ Surveillez les actions des utilisateurs via activity_logs
