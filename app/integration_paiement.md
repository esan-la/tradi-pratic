# 🗓️ SYSTÈME DE DISPONIBILITÉS - INTÉGRATION AVEC PAIEMENTS EXISTANTS

## ⚠️ IMPORTANT : INTÉGRATION AVEC LE SYSTÈME EXISTANT

Ce système utilise **votre table `payments` existante** au lieu d'en créer une nouvelle.

---

## 📊 ARCHITECTURE FINALE

```
USERS (conservé - existant)
   ↓
AVAILABILITY_PERIODS (nouveau - créneaux récurrents)
   ↓
EVENTS (nouveau - occupation calendrier)
   ↓
APPOINTMENTS (nouveau - données client)
   ↓
PAYMENTS (conservé - existant avec adaptation)
```

---

## 🔄 INTÉGRATION DES PAIEMENTS

### Option 1 : Colonne appointment_id (Recommandé)

Si votre table `payments` n'a pas encore de lien vers appointments :

```sql
ALTER TABLE payments 
ADD COLUMN appointment_id BIGINT UNSIGNED NULL 
AFTER id;

ALTER TABLE payments 
ADD CONSTRAINT fk_payments_appointments 
FOREIGN KEY (appointment_id) 
REFERENCES appointments(id) 
ON DELETE CASCADE;
```

**Migration fournie :** `2024_02_16_000001_add_appointment_to_payments.php`

---

### Option 2 : Polymorphic Relationship (Si déjà en place)

Si votre système utilise déjà `payable_type` et `payable_id` :

```php
// Dans votre modèle Payment existant
public function payable()
{
    return $this->morphTo();
}

// Créer un paiement pour un rendez-vous
$payment = Payment::create([
    'payable_type' => Appointment::class,
    'payable_id' => $appointment->id,
    'amount' => 10000,
    // ... autres champs
]);
```

---

## 📋 TABLES CRÉÉES

### 1. availability_periods ✅
### 2. events ✅
### 3. appointments ✅
### 4. payments ❌ (Utilise l'existant)

---

## 💡 EXEMPLES D'UTILISATION AVEC PAIEMENTS

### Créer un Rendez-vous avec Paiement

```php
use App\Models\Event;
use App\Models\Appointment;
use App\Models\Payment; // Votre modèle existant

DB::transaction(function() {
    // 1. Créer l'événement
    $event = Event::create([
        'admin_id' => 1,
        'title' => 'Consultation Mme Dupont',
        'event_type' => 'appointment',
        'start_datetime' => '2024-02-20 10:00:00',
        'end_datetime' => '2024-02-20 11:00:00',
        'status' => 'scheduled',
    ]);

    // 2. Créer le rendez-vous
    $appointment = Appointment::create([
        'event_id' => $event->id,
        'name' => 'Marie Dupont',
        'email' => 'marie@example.com',
        'phone' => '+226 70 12 34 56',
        'provenance' => 'Ouagadougou',
        'consultation_type' => 'traditional',
        'status' => 'pending',
    ]);

    // 3. Créer le paiement (avec votre logique existante)
    
    // Option A: Si appointment_id existe
    $payment = Payment::create([
        'appointment_id' => $appointment->id,
        'amount' => 10000,
        'status' => 'pending', // Utilisez votre champ de statut existant
        // ... autres champs selon votre table
    ]);

    // Option B: Si polymorphic
    $payment = Payment::create([
        'payable_type' => Appointment::class,
        'payable_id' => $appointment->id,
        'amount' => 10000,
        'status' => 'pending',
        // ... autres champs
    ]);
});
```

---

### Vérifier le Statut de Paiement

```php
$appointment = Appointment::find($id);

// Vérifier si payé (adapté à votre logique)
$isPaid = $appointment->payments()
    ->where('status', 'completed') // ou 'paid' selon votre système
    ->exists();

// Obtenir le montant total
$totalPaid = $appointment->payments()
    ->where('status', 'completed')
    ->sum('amount');
```

---

## 🔧 ADAPTATION DE VOTRE MODÈLE PAYMENT EXISTANT

Ajoutez ces méthodes à votre modèle `Payment` existant :

```php
// Dans app/Models/Payment.php (votre modèle existant)

/**
 * Relation avec appointment (si appointment_id existe)
 */
public function appointment()
{
    return $this->belongsTo(Appointment::class);
}

/**
 * OU relation polymorphic (si payable_type/payable_id existe)
 */
public function payable()
{
    return $this->morphTo();
}

/**
 * Scope pour les paiements de rendez-vous
 */
public function scopeForAppointments($query)
{
    // Option A: Direct
    return $query->whereNotNull('appointment_id');
    
    // Option B: Polymorphic
    return $query->where('payable_type', Appointment::class);
}
```

---

## 📊 STRUCTURE PAYMENT MINIMALE REQUISE

Votre table `payments` existante doit avoir AU MINIMUM :

```sql
-- Champs obligatoires (que vous avez probablement déjà)
id BIGINT UNSIGNED PRIMARY KEY
amount DECIMAL(10,2)
status VARCHAR(50)  -- ou payment_status, etc.
created_at TIMESTAMP
updated_at TIMESTAMP

-- Champ à ajouter (UNE des deux options)
-- Option A: Lien direct
appointment_id BIGINT UNSIGNED NULL

-- Option B: Polymorphic (si vous l'utilisez déjà)
payable_type VARCHAR(255) NULL
payable_id BIGINT UNSIGNED NULL
```

---

## 🚀 ÉTAPES D'INSTALLATION

### 1. Exécuter les migrations

```bash
# Migration principale (availability, events, appointments)
php artisan migrate

# Migration optionnelle (ajoute appointment_id à payments)
# Seulement si vous choisissez l'Option A
php artisan migrate --path=database/migrations/2024_02_16_000001_add_appointment_to_payments.php
```

### 2. Adapter votre modèle Payment

Ajoutez la relation vers `Appointment` dans votre modèle `Payment` existant.

### 3. Tester

```php
// Créer un rendez-vous de test
$event = Event::create([...]);
$appointment = Appointment::create([...]);
$payment = Payment::create([
    'appointment_id' => $appointment->id,
    'amount' => 5000,
]);

// Vérifier la relation
dd($appointment->payments);
dd($payment->appointment);
```

---

## 📝 DIFFÉRENCES AVEC LE SYSTÈME INITIAL

| Aspect | Système Initial | Système Adapté |
|--------|----------------|----------------|
| Table payments | Nouvelle table créée | Utilise table existante |
| Migration payments | Incluse | Optionnelle (adaptation) |
| Modèle Payment | Nouveau modèle créé | Utilise modèle existant |
| Relations | appointment_id fixe | Flexible (direct ou polymorphic) |
| Champs | Standardisés | Adaptés à votre existant |

---

## ⚙️ CONFIGURATION RECOMMANDÉE

### Dans config/app.php (si nécessaire)

```php
'aliases' => [
    // ... autres alias
    'Payment' => App\Models\Payment::class, // Votre modèle existant
],
```

### Variables d'environnement

```env
# Si vous utilisez un service de paiement externe
PAYMENT_GATEWAY=stripe
PAYMENT_CURRENCY=XOF
PAYMENT_CONVERSION_RATE=1
```

---

## 🔗 COMPATIBILITÉ

Ce système est compatible avec :

- ✅ Orange Money
- ✅ MTN Mobile Money
- ✅ Moov Money
- ✅ Wave
- ✅ Stripe
- ✅ PayPal
- ✅ Paiements en cash
- ✅ Tout autre système de paiement existant

Le système s'adapte à **votre logique de paiement actuelle** sans la modifier.

---

## 📞 SUPPORT

Si vous avez besoin d'adapter davantage le système à votre logique de paiement existante, voici les points d'intégration clés :

1. **Modèle Appointment** : Méthode `payments()` - ligne 32
2. **Migration optionnelle** : `2024_02_16_000001_add_appointment_to_payments.php`
3. **Votre modèle Payment** : Ajouter méthode `appointment()`

---

## ✅ AVANTAGES DE CETTE APPROCHE

- ✅ **Pas de duplication** : Réutilise votre table payments
- ✅ **Pas de migration de données** : Vos paiements existants ne sont pas touchés
- ✅ **Flexible** : S'adapte à votre structure actuelle
- ✅ **Rétrocompatible** : Les anciens paiements continuent de fonctionner
- ✅ **Évolutif** : Permet d'ajouter appointments aux paiements futurs

**Le système est prêt à s'intégrer à votre infrastructure de paiement existante !** ✅🚀
