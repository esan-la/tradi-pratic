<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\RealisationController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\RecipeController as AdminRecipeController;
use App\Http\Controllers\Admin\RealisationController as AdminRealisationController;
// use App\Http\Controllers\Admin\AppointmentController;

// Pages publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/a-propos', [AboutController::class, 'index'])->name('about');

// Consultations
Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations');
Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');
Route::post('/consultations/check-availability', [ConsultationController::class, 'checkAvailability'])->name('consultations.check-availability');

// Réalisations
Route::get('/realisations', [RealisationController::class, 'index'])->name('realisations');
Route::get('/realisations/{slug}', [RealisationController::class, 'show'])->name('realisations.show');

// Recettes
Route::get('/recettes', [RecipeController::class, 'index'])->name('recipes');
Route::get('/recettes/{slug}', [RecipeController::class, 'show'])->name('recipes.show');

// Médias
Route::get('/medias', [MediaController::class, 'index'])->name('media');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Admin
// Route::post('appointments', AppointmentController::class, 'store')->name('appointments.create');
// Route::post('appointments', [AdminAppointmentController::class, 'create'])->name('appointments.create');
// Routes pour les utilisateurs non connectés (guest)
Route::middleware('guest')->group(function () {
    /**
     * Affiche le formulaire de connexion.
     * Accessible via l'URL /login.
     * Nom de la route : login
     */
    Route::get('login', [LoginController::class, 'create'])
        ->name('login');

    /**
     * Traite la soumission du formulaire de connexion.
     * Redirige l'utilisateur vers admin.dashboard en cas de succès.
     */
    Route::post('login', [LoginController::class, 'store']);
});

// Routes pour les utilisateurs connectés (auth)
Route::middleware('auth')->group(function () {
    /**
     * Gère la déconnexion de l'utilisateur.
     * Doit être appelée via une méthode POST (pour des raisons de sécurité).
     * Nom de la route : logout
     */
    Route::post('logout', [LoginController::class, 'destroy'])
        ->name('logout');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Appointments
    Route::resource('appointments', AdminAppointmentController::class);
    Route::post('appointments/{appointment}/update-status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.update-status');

    // Recipes
    Route::resource('recipes', AdminRecipeController::class);

    // Realisations
    Route::resource('realisations', AdminRealisationController::class);
});

require __DIR__.'/auth.php';
