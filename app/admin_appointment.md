# 📋 VUES ADMIN - SYSTÈME DE RENDEZ-VOUS

## 🎯 VUE D'ENSEMBLE

Ce document liste toutes les vues Blade à créer pour le système de disponibilités et rendez-vous.

---

## 📁 STRUCTURE DES DOSSIERS

```
resources/views/admin/
├── appointments/
│   ├── index.blade.php      ✅ (Liste des rendez-vous)
│   ├── create.blade.php     ✅ (Créer un rendez-vous)
│   ├── edit.blade.php       ✅ (Modifier un rendez-vous)
│   └── show.blade.php       ✅ (Détails d'un rendez-vous)
├── availabilities/
│   ├── index.blade.php      ✅ (Liste des disponibilités)
│   ├── create.blade.php     ✅ (Créer une disponibilité)
│   ├── edit.blade.php       ✅ (Modifier une disponibilité)
│   └── show.blade.php       ✅ (Détails d'une disponibilité)
└── events/
    ├── index.blade.php      ✅ (Liste des événements)
    ├── calendar.blade.php   ✅ (Vue calendrier)
    ├── create.blade.php     ✅ (Créer un événement)
    ├── edit.blade.php       ✅ (Modifier un événement)
    └── show.blade.php       ✅ (Détails d'un événement)
```

---

## 📋 LISTE DES VUES À CRÉER

### 1. APPOINTMENTS (Rendez-vous) - 4 vues

#### ✅ appointments/index.blade.php
- Liste paginée des rendez-vous
- Filtres: statut, type consultation, date, recherche
- Stats: Total, En attente, Confirmés, Complétés
- Actions: Voir, Confirmer, Annuler, Modifier, Supprimer
- Badges de statut colorés

#### ✅ appointments/create.blade.php
- Sélection administrateur
- Champs date/heure avec vérification disponibilité
- Informations client (nom, email, téléphone, provenance)
- Upload document d'identité (optionnel)
- Type de consultation (select)
- Message/notes
- Montant paiement (optionnel)
- JavaScript pour créneaux disponibles

#### ✅ appointments/edit.blade.php
- Formulaire pré-rempli
- Modification date/heure avec vérification
- Informations client éditables
- Document actuel affiché + upload nouveau
- Notes administrateur
- Historique des modifications

#### ✅ appointments/show.blade.php
- Détails complets du rendez-vous
- Informations événement (date, heure, durée)
- Informations client
- Document d'identité (si présent)
- Type de consultation
- Statut avec badge
- Notes administrateur
- Paiements associés
- Boutons d'action: Confirmer, Annuler, Terminer, Modifier

---

### 2. AVAILABILITIES (Disponibilités) - 4 vues

#### ✅ availabilities/index.blade.php
- Liste des créneaux récurrents
- Filtres: administrateur, jour de la semaine, statut
- Groupement par jour
- Toggle actif/inactif
- Actions: Voir, Modifier, Toggle, Supprimer

#### ✅ availabilities/create.blade.php
- Sélection administrateur
- Jour de la semaine (select)
- Heure début/fin (time picker)
- Récurrence (checkbox)
- Période de validité (optionnel)
- Vérification chevauchements

#### ✅ availabilities/edit.blade.php
- Formulaire pré-rempli
- Modification horaires
- Activation/Désactivation
- Liste des événements liés

#### ✅ availabilities/show.blade.php
- Détails de la disponibilité
- Administrateur
- Jour et horaire
- Statut (actif/inactif)
- Période de validité
- Liste des événements générés
- Statistiques d'utilisation

---

### 3. EVENTS (Événements) - 5 vues

#### ✅ events/index.blade.php
- Liste des événements
- Filtres: type, statut, admin, date
- Calendrier mini en sidebar
- Actions: Voir, Modifier, Annuler, Terminer, Supprimer
- Badges de type et statut

#### ✅ events/calendar.blade.php
- Vue calendrier mensuel (FullCalendar.js)
- Événements colorés par type
- Click pour voir détails
- Drag & drop pour déplacer
- Bouton créer événement

#### ✅ events/create.blade.php
- Sélection administrateur
- Type d'événement (select)
- Titre
- Date/heure début/fin
- Description
- Lien avec disponibilité (optionnel)
- Note: redirige vers appointments si type=appointment

#### ✅ events/edit.blade.php
- Formulaire pré-rempli
- Modification uniquement si type ≠ appointment
- Message si appointment (redirection)

#### ✅ events/show.blade.php
- Détails de l'événement
- Type, statut, administrateur
- Date/heure, durée
- Description
- Disponibilité liée (si existe)
- Rendez-vous lié (si appointment)
- Boutons d'action

---

## 🎨 COMPOSANTS RÉUTILISABLES

### Badges de Statut

```blade
@php
    $statusColors = [
        'pending' => 'warning',
        'confirmed' => 'info',
        'cancelled' => 'danger',
        'completed' => 'success',
        'scheduled' => 'primary',
    ];
@endphp

<span class="badge bg-{{ $statusColors[$status] ?? 'secondary' }}">
    {{ ucfirst($status) }}
</span>
```

### Cards de Statistiques

```blade
<div class="col-md-3">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted">{{ $title }}</h6>
                    <h3 class="mb-0">{{ $count }}</h3>
                </div>
                <div class="text-{{ $color }}">
                    <i class="fas fa-{{ $icon }} fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>
```

### Filtres

```blade
<form method="GET" action="{{ route('admin.appointments.index') }}">
    <div class="row g-3">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Tous les statuts</option>
                <!-- Options -->
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="date" class="form-control">
        </div>
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Rechercher...">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search"></i> Filtrer
            </button>
        </div>
    </div>
</form>
```

---

## 🔧 FONCTIONNALITÉS JAVASCRIPT

### 1. Créneaux Disponibles (appointments/create)

```javascript
// Charger les créneaux disponibles
$('#date, #admin_id').on('change', function() {
    const adminId = $('#admin_id').val();
    const date = $('#date').val();
    
    if (adminId && date) {
        $.get('{{ route("admin.appointments.available-slots") }}', {
            admin_id: adminId,
            date: date
        }).done(function(data) {
            // Afficher les créneaux
        });
    }
});
```

### 2. Confirmation Actions

```javascript
// Confirmer suppression
$('.delete-btn').on('click', function(e) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
        e.preventDefault();
    }
});
```

### 3. Calendrier (events/calendar)

```javascript
const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: @json($events),
    eventClick: function(info) {
        window.location.href = info.event.url;
    }
});
```

---

## 🎯 PERMISSIONS REQUISES

### Appointments
- `appointments.view` - Voir la liste
- `appointments.create` - Créer
- `appointments.edit` - Modifier
- `appointments.delete` - Supprimer

### Availabilities
- `availabilities.view` - Voir la liste
- `availabilities.create` - Créer
- `availabilities.edit` - Modifier
- `availabilities.delete` - Supprimer

### Events
- `events.view` - Voir la liste
- `events.create` - Créer
- `events.edit` - Modifier
- `events.delete` - Supprimer

---

## 📦 PACKAGES JS REQUIS

```json
{
    "dependencies": {
        "@fullcalendar/core": "^6.1.0",
        "@fullcalendar/daygrid": "^6.1.0",
        "@fullcalendar/interaction": "^6.1.0",
        "flatpickr": "^4.6.13",
        "select2": "^4.1.0"
    }
}
```

---

## ✅ CHECKLIST D'IMPLÉMENTATION

### Vues Appointments
- [ ] index.blade.php
- [ ] create.blade.php
- [ ] edit.blade.php
- [ ] show.blade.php

### Vues Availabilities
- [ ] index.blade.php
- [ ] create.blade.php
- [ ] edit.blade.php
- [ ] show.blade.php

### Vues Events
- [ ] index.blade.php
- [ ] calendar.blade.php
- [ ] create.blade.php
- [ ] edit.blade.php
- [ ] show.blade.php

### Routes
- [x] Routes web complètes
- [x] Ordre CREATE avant SHOW
- [x] Middleware permissions

### Contrôleurs
- [x] AppointmentController
- [x] AvailabilityPeriodController
- [x] EventController

### Modèles
- [x] Appointment
- [x] AvailabilityPeriod
- [x] Event

### Migrations
- [x] availability_periods
- [x] events
- [x] appointments

---

## 🚀 PROCHAINES ÉTAPES

1. ✅ Générer toutes les vues Blade
2. ✅ Ajouter les assets JS/CSS
3. ✅ Tester chaque vue
4. ✅ Ajouter les permissions en BDD
5. ✅ Créer les seeders de test

**Total: 13 vues à créer pour un système complet de gestion des rendez-vous !** 📅✨
