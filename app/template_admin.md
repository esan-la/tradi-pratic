# Template Admin - TradiPratic

Template d'administration professionnel avec gestion complète des rôles et permissions.

## 📋 Caractéristiques

### ✨ Design Moderne
- Sidebar sombre élégante avec navigation organisée
- Profil utilisateur avec avatar et rôle
- Cartes statistiques animées
- Tables responsives avec actions rapides
- Alertes personnalisées avec icônes
- Loading overlay pour les actions asynchrones
- Design mobile-first complètement responsive

### 🔐 Gestion des Permissions
- Contrôle d'accès basé sur les permissions
- Menu dynamique selon les droits de l'utilisateur
- Badges de notification (commandes en attente, nouveaux messages, etc.)
- Affichage du rôle utilisateur dans le sidebar

### 📊 Navigation Organisée

#### 1. **Tableau de bord**
- Vue d'ensemble globale

#### 2. **Hôtellerie** (hotels.*, reservations.*)
- Hôtels
- Réservations

#### 3. **E-Commerce** (products.*, orders.*)
- Produits
- Commandes

#### 4. **Dons** (donations.*)
- Donateurs
- Dons

#### 5. **Finances** (payments.*)
- Paiements

#### 6. **Communication** (contacts.*)
- Messages de contact

#### 7. **Administration** (users.*, roles.*, settings.*, logs.*)
- Utilisateurs
- Rôles & Permissions
- Paramètres
- Journaux d'activité

## 🚀 Installation

### 1. Copier le template
```bash
cp admin_layout.blade.php votre-projet/resources/views/layouts/admin.blade.php
```

### 2. Structure des dossiers recommandée
```
resources/views/
├── layouts/
│   └── admin.blade.php
├── admin/
│   ├── dashboard.blade.php
│   ├── hotels/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── orders/
│   ├── donations/
│   ├── payments/
│   ├── contacts/
│   └── settings/
```

## 📝 Utilisation

### Template de base

```blade
@extends('layouts.admin')

@section('title', 'Ma Page')

@section('page-title', 'Titre de la Page')
@section('page-description', 'Description de la page')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Section</a></li>
    <li class="breadcrumb-item active">Ma Page</li>
@endsection

@section('page-actions')
    <a href="#" class="btn btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Nouvelle action
    </a>
@endsection

@section('content')
    <!-- Votre contenu ici -->
@endsection

@push('styles')
    <!-- CSS personnalisé -->
@endpush

@push('scripts')
    <!-- JavaScript personnalisé -->
@endpush
```

### Exemple avec contrôle de permissions

```blade
@extends('layouts.admin')

@section('content')
    @if(Auth::user()->hasPermission('orders.view'))
        <!-- Contenu pour utilisateurs avec permission -->
    @else
        <div class="alert alert-warning">
            Vous n'avez pas accès à cette section.
        </div>
    @endif
@endsection
```

## 🎨 Composants Disponibles

### 1. Cartes Statistiques

```blade
<div class="row g-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <h3>150 000 FCFA</h3>
            <p>Revenus du mois</p>
            <div class="trend text-success">
                <i class="fas fa-arrow-up"></i> +12.5%
            </div>
        </div>
    </div>
</div>
```

### 2. Cartes Personnalisées

```blade
<div class="custom-card">
    <div class="card-header">
        <h5 class="mb-0">Titre de la carte</h5>
    </div>
    <div class="card-body">
        <!-- Contenu -->
    </div>
</div>
```

### 3. Tables

```blade
<div class="table-responsive">
    <table class="table custom-table">
        <thead>
            <tr>
                <th>Colonne 1</th>
                <th>Colonne 2</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Donnée 1</td>
                <td>Donnée 2</td>
                <td>
                    <div class="table-actions">
                        <a href="#" class="btn btn-sm btn-action btn-outline-primary" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-action btn-outline-success" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-action btn-outline-danger" title="Supprimer" onclick="return confirmDelete()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

### 4. Badges de Statut

```blade
<!-- Statuts prédéfinis -->
<span class="badge badge-status bg-success">Confirmé</span>
<span class="badge badge-status bg-warning">En attente</span>
<span class="badge badge-status bg-danger">Annulé</span>
<span class="badge badge-status bg-info">En cours</span>
<span class="badge badge-status bg-primary">Nouveau</span>
```

### 5. Alertes

```blade
<div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>Opération réussie !
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
```

### 6. Boutons

```blade
<!-- Bouton primaire personnalisé -->
<button class="btn btn-primary-custom">
    <i class="fas fa-save me-2"></i>Enregistrer
</button>

<!-- Bouton standard -->
<button class="btn btn-custom btn-success">Action</button>

<!-- Bouton d'action dans table -->
<button class="btn btn-sm btn-action btn-outline-danger">
    <i class="fas fa-trash"></i>
</button>
```

## 🔧 Fonctions JavaScript Disponibles

### Loading Overlay

```javascript
// Afficher le loading
window.showLoading();

// Masquer le loading
window.hideLoading();

// Exemple avec AJAX
$.ajax({
    url: '/api/data',
    beforeSend: function() {
        showLoading();
    },
    success: function(data) {
        // Traiter les données
    },
    complete: function() {
        hideLoading();
    }
});
```

### Confirmation de Suppression

```javascript
// Utilisation simple
<button onclick="return confirmDelete()">Supprimer</button>

// Avec message personnalisé
<button onclick="return confirmDelete('Voulez-vous vraiment supprimer cet élément ?')">
    Supprimer
</button>
```

### Tooltips Bootstrap

```blade
<!-- Ajouter data-bs-toggle="tooltip" -->
<button data-bs-toggle="tooltip" title="Information complémentaire">
    <i class="fas fa-info-circle"></i>
</button>
```

## 🎯 Variables Partagées

Pour afficher les compteurs de notifications dans le sidebar, définissez ces variables dans votre `AppServiceProvider` :

```php
// app/Providers/AppServiceProvider.php
use Illuminate\Support\Facades\View;

public function boot()
{
    View::composer('layouts.admin', function ($view) {
        if (Auth::check()) {
            $view->with([
                'pendingReservations' => HotelReservation::where('status', 'pending')->count(),
                'pendingOrders' => Order::where('status', 'pending')->count(),
                'newContacts' => Contact::where('status', 'new')->count(),
            ]);
        }
    });
}
```

## 🔒 Middleware de Protection

Protégez vos routes admin avec le middleware approprié :

```php
// routes/web.php
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes avec permission spécifique
    Route::middleware('permission:hotels.view')->group(function () {
        Route::resource('hotels', HotelController::class);
    });
    
    Route::middleware('permission:orders.view')->group(function () {
        Route::resource('orders', OrderController::class);
    });
});
```

## 📱 Responsive Design

Le template est entièrement responsive :
- **Desktop** : Sidebar fixe à gauche (280px)
- **Tablet** : Sidebar repliable
- **Mobile** : Sidebar en overlay avec bouton toggle

## 🎨 Personnalisation des Couleurs

Modifiez les variables CSS dans le `<style>` :

```css
:root {
    --sidebar-width: 280px;
    --primary-color: #2d6a4f;        /* Couleur principale */
    --secondary-color: #40916c;       /* Couleur secondaire */
    --accent-color: #d4af37;          /* Couleur d'accent (or) */
    --sidebar-bg: #1a1a2e;            /* Fond sidebar */
    --sidebar-hover: #16213e;         /* Hover sidebar */
}
```

## 🔍 Sections du Menu

Le sidebar est organisé en sections logiques :

1. **Dashboard** - Toujours visible
2. **Hôtellerie** - Si permissions hotels.* ou reservations.*
3. **E-Commerce** - Si permissions products.* ou orders.*
4. **Dons** - Si permission donations.*
5. **Finances** - Si permission payments.*
6. **Communication** - Si permission contacts.*
7. **Administration** - Si permissions users.*, roles.*, settings.*, logs.*
8. **Autre** - Lien site public et déconnexion

## ⚡ Optimisations

### Lazy Loading des Scripts

```blade
@push('scripts')
<script>
    // Charger seulement si nécessaire
    if (document.getElementById('myChart')) {
        // Initialiser le graphique
    }
</script>
@endpush
```

### Cache des Permissions

Pour améliorer les performances, mettez en cache les permissions :

```php
// Dans votre User model
public function hasPermission($permission)
{
    return Cache::remember("user.{$this->id}.permissions", 3600, function () use ($permission) {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    });
}
```

## 📚 Exemples Complets

Consultez `dashboard_example.blade.php` pour un exemple complet d'utilisation du template avec :
- Cartes statistiques
- Graphiques Chart.js
- Tables avec données
- Vérification des permissions
- Actions rapides

## 🆘 Support

Pour toute question ou problème :
1. Vérifiez que toutes les migrations sont exécutées
2. Vérifiez que les seeders sont exécutés (rôles et permissions)
3. Assurez-vous que l'utilisateur a au moins un rôle assigné
4. Consultez les logs Laravel pour les erreurs

## 📝 Notes Importantes

- Les permissions doivent être définies exactement comme dans le `PermissionSeeder`
- Utilisez toujours `@if(Auth::user()->hasPermission('...'))` pour les vérifications
- Les badges de notification nécessitent les variables partagées définies
- Le template nécessite Bootstrap 5 et Font Awesome 6
