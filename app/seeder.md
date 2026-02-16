# Seeders - Users, Roles & Permissions

Ce dossier contient les seeders pour initialiser le système d'authentification et d'autorisation.

## 📋 Contenu

### Seeders
1. **PermissionSeeder** - Crée toutes les permissions du système
2. **RoleSeeder** - Crée les rôles et les associe aux permissions
3. **UserSeeder** - Crée les utilisateurs par défaut et les associe aux rôles
4. **DatabaseSeeder** - Seeder principal qui appelle tous les autres

### Migrations (à utiliser avant les seeders)
1. **create_users_table** - Table des utilisateurs
2. **create_roles_table** - Table des rôles
3. **create_permissions_table** - Table des permissions
4. **create_role_user_table** - Table pivot role-user
5. **create_permission_role_table** - Table pivot permission-role

## 🚀 Installation

### 1. Copier les migrations
```bash
cp migrations/2024_01_01_000000_create_users_table.php votre-projet/database/migrations/
cp migrations/2024_01_01_000001_create_roles_table.php votre-projet/database/migrations/
cp migrations/2024_01_01_000002_create_permissions_table.php votre-projet/database/migrations/
cp migrations/2024_01_01_000003_create_role_user_table.php votre-projet/database/migrations/
cp migrations/2024_01_01_000004_create_permission_role_table.php votre-projet/database/migrations/
```

### 2. Copier les seeders
```bash
cp seeders/*.php votre-projet/database/seeders/
```

### 3. Exécuter les migrations
```bash
php artisan migrate
```

### 4. Exécuter les seeders
```bash
php artisan db:seed
# OU
php artisan db:seed --class=DatabaseSeeder
```

### 5. (Optionnel) Tout réinitialiser et recréer
```bash
php artisan migrate:fresh --seed
```

## 👥 Utilisateurs créés

| Nom | Email | Mot de passe | Rôle |
|-----|-------|--------------|------|
| Super Admin | superadmin@tradipratic.com | SuperAdmin@2024 | super_admin |
| Administrateur Principal | admin@tradipratic.com | Admin@2024 | admin |
| Gestionnaire | manager@tradipratic.com | Manager@2024 | manager |
| Réceptionniste | receptionist@tradipratic.com | Reception@2024 | receptionist |
| Service Client | support@tradipratic.com | Support@2024 | customer_service |

⚠️ **Important** : Changez ces mots de passe en production !

## 🎭 Rôles créés

### 1. Super Admin
**Description** : Accès total au système

**Permissions** : Toutes (43 permissions)

### 2. Admin
**Description** : Gestion du contenu et des utilisateurs

**Permissions** : Toutes sauf :
- users.delete
- roles.delete

### 3. Manager
**Description** : Gestion des réservations et commandes

**Permissions** :
- Tous les produits (products.*)
- Toutes les commandes (orders.*)
- Toutes les réservations (reservations.*)
- Tous les dons (donations.*)
- Visualisation des paiements (payments.view)

### 4. Receptionist
**Description** : Gestion des réservations d'hôtel

**Permissions** :
- Tous les hôtels (hotels.*)
- Toutes les réservations (reservations.*)
- Visualisation des paiements (payments.view)

### 5. Customer Service
**Description** : Gestion des contacts et support

**Permissions** :
- Tous les contacts (contacts.*)

## 🔑 Permissions disponibles (43 au total)

### Users (4)
- users.view
- users.create
- users.edit
- users.delete

### Roles (4)
- roles.view
- roles.create
- roles.edit
- roles.delete

### Hotels (4)
- hotels.view
- hotels.create
- hotels.edit
- hotels.delete

### Reservations (6)
- reservations.view
- reservations.create
- reservations.edit
- reservations.delete
- reservations.confirm
- reservations.cancel

### Products (4)
- products.view
- products.create
- products.edit
- products.delete

### Orders (5)
- orders.view
- orders.create
- orders.edit
- orders.delete
- orders.update-status

### Donations (5)
- donations.view
- donations.create
- donations.edit
- donations.delete
- donations.receive

### Payments (2)
- payments.view
- payments.process

### Contacts (3)
- contacts.view
- contacts.reply
- contacts.delete

### Settings (2)
- settings.view
- settings.edit

### Logs (1)
- logs.view

## 🔒 Utilisation dans le code

### Vérifier un rôle
```php
// Dans un contrôleur
if (auth()->user()->hasRole('admin')) {
    // Code pour admin
}

// Dans une vue Blade
@if(auth()->user()->hasRole('super_admin'))
    <!-- Contenu pour super admin -->
@endif
```

### Vérifier une permission
```php
// Dans un contrôleur
if (auth()->user()->hasPermission('products.create')) {
    // L'utilisateur peut créer des produits
}

// Dans une vue Blade
@if(auth()->user()->hasPermission('orders.edit'))
    <button>Modifier la commande</button>
@endif
```

### Middleware (à créer)
```php
// app/Http/Middleware/CheckRole.php
public function handle($request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return redirect('login');
    }

    foreach ($roles as $role) {
        if (auth()->user()->hasRole($role)) {
            return $next($request);
        }
    }

    abort(403, 'Action non autorisée.');
}

// Utilisation dans routes/web.php
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::resource('users', UserController::class);
});
```

### Middleware Permission
```php
// app/Http/Middleware/CheckPermission.php
public function handle($request, Closure $next, $permission)
{
    if (!auth()->check()) {
        return redirect('login');
    }

    if (!auth()->user()->hasPermission($permission)) {
        abort(403, 'Action non autorisée.');
    }

    return $next($request);
}

// Utilisation
Route::middleware(['auth', 'permission:products.create'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create']);
});
```

## 📦 Modèles à créer

Vous aurez besoin de créer les modèles suivants avec les relations :

### User.php
```php
public function roles()
{
    return $this->belongsToMany(Role::class, 'role_user');
}

public function hasRole($role)
{
    return $this->roles()->where('name', $role)->exists();
}

public function hasPermission($permission)
{
    return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
        $query->where('name', $permission);
    })->exists();
}
```

### Role.php
```php
public function users()
{
    return $this->belongsToMany(User::class, 'role_user');
}

public function permissions()
{
    return $this->belongsToMany(Permission::class, 'permission_role');
}
```

### Permission.php
```php
public function roles()
{
    return $this->belongsToMany(Role::class, 'permission_role');
}
```

## 🔄 Commandes utiles

```bash
# Seeder spécifique
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=UserSeeder

# Tout réinitialiser
php artisan migrate:fresh --seed

# Rollback et re-seed
php artisan migrate:rollback
php artisan migrate
php artisan db:seed
```

## ⚠️ Notes importantes

1. **Ordre d'exécution** : Les seeders doivent être exécutés dans l'ordre :
   - PermissionSeeder
   - RoleSeeder (dépend des permissions)
   - UserSeeder (dépend des rôles)

2. **Production** : En production, changez **TOUS** les mots de passe par défaut

3. **Personnalisation** : Adaptez les permissions selon vos besoins spécifiques

4. **Extension** : Pour ajouter de nouvelles permissions, modifiez simplement le PermissionSeeder et relancez-le

## 🎯 Prochaines étapes

1. Créer les modèles User, Role et Permission avec les relations
2. Créer les middlewares CheckRole et CheckPermission
3. Protéger vos routes avec ces middlewares
4. Créer une interface d'administration pour gérer les rôles et permissions
5. Implémenter l'authentification (Laravel Breeze, Jetstream, etc.)
