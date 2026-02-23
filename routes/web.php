<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\RealisationController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\SocialLinkController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\PubServiceController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HotelReservationController;


// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\RecipeController as AdminRecipeController;
use App\Http\Controllers\Admin\RealisationController as AdminRealisationController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\HotelReservationController as AdminHotelReservationController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\DonorController as AdminDonorController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\SocialLinkController as AdminSocialLinkController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\BibliographyController as AdminBibliographyController;
use App\Http\Controllers\Admin\PubServiceController as AdminPubServiceController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\AvailabilityPeriodController as AdminAvailabilityPeriodController;
use App\Http\Controllers\Admin\EventController as AdminEventController;

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/

// Pages principales
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

// Publicité de services
Route::get('/services', [PubServiceController::class, 'index'])->name('pub-services.index');
Route::get('/services/{slug}', [PubServiceController::class, 'show'])->name('pub-services.show');
Route::post('/services/{service}/contact', [PubServiceController::class, 'contact'])->name('pub-services.contact');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Dons publics (sans authentification requise)
Route::get('/faire-un-don', [DonationController::class, 'publicForm'])->name('donate');
Route::post('/faire-un-don', [DonationController::class, 'publicStore'])->name('donate.store');

// Témoignages publics
Route::post('/temoignages', [TestimonialController::class, 'publicStore'])->name('testimonials.store');

// Reservation de l'hôtel
Route::get('hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');
Route::get('hotel-reservations/create', [HotelReservationController::class, 'create'])->name('hotel-reservations.create');
Route::post('hotel-reservations', [HotelReservationController::class, 'store'])->name('hotel-reservations.store');

/*
|--------------------------------------------------------------------------
| ROUTES D'AUTHENTIFICATION
|--------------------------------------------------------------------------
*/

// Routes pour les utilisateurs non connectés (guest)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

// Routes pour les utilisateurs connectés
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| ROUTES ADMINISTRATION
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/stats', [DashboardController::class, 'getStats'])->name('stats');
    Route::get('/chart-data', [DashboardController::class, 'getChartData'])->name('chart-data');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    /*
    |--------------------------------------------------------------------------
    | HÔTELLERIE
    |--------------------------------------------------------------------------
    */

    // Hôtels - CREATE en premier (avant show)
    Route::middleware('permission:hotels.create')->group(function () {
        Route::get('hotels/create', [AdminHotelController::class, 'create'])->name('hotels.create');
        Route::post('hotels', [AdminHotelController::class, 'store'])->name('hotels.store');
    });

    Route::middleware('permission:hotels.edit')->group(function () {
        Route::get('hotels/{hotel}/edit', [AdminHotelController::class, 'edit'])->name('hotels.edit');
        Route::put('hotels/{hotel}', [AdminHotelController::class, 'update'])->name('hotels.update');
    });

    Route::middleware('permission:hotels.delete')->group(function () {
        Route::delete('hotels/{hotel}', [AdminHotelController::class, 'destroy'])->name('hotels.destroy');
    });

    Route::middleware('permission:hotels.view')->group(function () {
        Route::get('hotels', [AdminHotelController::class, 'index'])->name('hotels.index');
        Route::get('hotels/{hotel}', [AdminHotelController::class, 'show'])->name('hotels.show');
    });

    // Réservations d'hôtel - CREATE en premier
    Route::middleware('permission:reservations.create')->group(function () {
        Route::get('hotel-reservations/create', [AdminHotelReservationController::class, 'create'])->name('hotel-reservations.create');
        Route::post('hotel-reservations', [AdminHotelReservationController::class, 'store'])->name('hotel-reservations.store');
    });

    Route::middleware('permission:reservations.confirm')->group(function () {
        Route::post('hotel-reservations/{hotelReservation}/confirm', [AdminHotelReservationController::class, 'confirm'])->name('hotel-reservations.confirm');
    });

    Route::middleware('permission:reservations.cancel')->group(function () {
        Route::post('hotel-reservations/{hotelReservation}/cancel', [AdminHotelReservationController::class, 'cancel'])->name('hotel-reservations.cancel');
    });

    Route::middleware('permission:reservations.delete')->group(function () {
        Route::delete('hotel-reservations/{hotelReservation}', [AdminHotelReservationController::class, 'destroy'])->name('hotel-reservations.destroy');
    });

    Route::middleware('permission:reservations.view')->group(function () {
        Route::get('hotel-reservations', [AdminHotelReservationController::class, 'index'])->name('hotel-reservations.index');
        Route::get('hotel-reservations/{hotelReservation}', [AdminHotelReservationController::class, 'show'])->name('hotel-reservations.show');
    });

    /*
    |--------------------------------------------------------------------------
    | E-COMMERCE
    |--------------------------------------------------------------------------
    */

    // Produits - CREATE en premier
    Route::middleware('permission:products.create')->group(function () {
        Route::get('products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('products', [AdminProductController::class, 'store'])->name('products.store');
    });

    Route::middleware('permission:products.edit')->group(function () {
        Route::get('products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    });

    Route::middleware('permission:products.delete')->group(function () {
        Route::delete('products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    });

    Route::middleware('permission:products.view')->group(function () {
        Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [AdminProductController::class, 'show'])->name('products.show');
    });

    // Commandes - CREATE en premier
    Route::middleware('permission:orders.create')->group(function () {
        Route::get('orders/create', [AdminOrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [AdminOrderController::class, 'store'])->name('orders.store');
    });

    Route::middleware('permission:orders.update-status')->group(function () {
        Route::post('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    });

    Route::middleware('permission:orders.delete')->group(function () {
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
    });

    Route::middleware('permission:orders.view')->group(function () {
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    });

    /*
    |--------------------------------------------------------------------------
    | DONS
    |--------------------------------------------------------------------------
    */

    // Donateurs - CREATE en premier
    Route::middleware('permission:donations.view')->group(function () {
        Route::get('donors/create', [AdminDonorController::class, 'create'])->name('donors.create');
        Route::post('donors', [AdminDonorController::class, 'store'])->name('donors.store');
        Route::get('donors/{donor}/edit', [AdminDonorController::class, 'edit'])->name('donors.edit');
        Route::put('donors/{donor}', [AdminDonorController::class, 'update'])->name('donors.update');
        Route::delete('donors/{donor}', [AdminDonorController::class, 'destroy'])->name('donors.destroy');
        Route::get('donors', [AdminDonorController::class, 'index'])->name('donors.index');
        Route::get('donors/{donor}', [AdminDonorController::class, 'show'])->name('donors.show');
    });

    // Dons - CREATE en premier
    Route::middleware('permission:donations.create')->group(function () {
        Route::get('donations/create', [AdminDonationController::class, 'create'])->name('donations.create');
        Route::post('donations', [AdminDonationController::class, 'store'])->name('donations.store');
    });

    Route::middleware('permission:donations.receive')->group(function () {
        Route::post('donations/{donation}/receive', [AdminDonationController::class, 'receive'])->name('donations.receive');
    });

    Route::middleware('permission:donations.delete')->group(function () {
        Route::delete('donations/{donation}', [AdminDonationController::class, 'destroy'])->name('donations.destroy');
    });

    Route::middleware('permission:donations.view')->group(function () {
        Route::get('donations', [AdminDonationController::class, 'index'])->name('donations.index');
        Route::get('donations/{donation}', [AdminDonationController::class, 'show'])->name('donations.show');
    });

    /*
    |--------------------------------------------------------------------------
    | PAIEMENTS
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:payments.process')->group(function () {
        Route::post('payments/order/{order}', [AdminPaymentController::class, 'processOrderPayment'])->name('payments.order');
        Route::post('payments/hotel/{reservation}', [AdminPaymentController::class, 'processHotelPayment'])->name('payments.hotel');
        Route::post('payments/donation', [AdminPaymentController::class, 'processDonationPayment'])->name('payments.donation');
    });

    Route::middleware('permission:payments.view')->group(function () {
        Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    });

    /*
    |--------------------------------------------------------------------------
    | COMMUNICATION
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:contacts.reply')->group(function () {
        Route::post('contacts/{contact}/reply', [AdminContactController::class, 'reply'])->name('contacts.reply');
        Route::post('contacts/{contact}/add-note', [AdminContactController::class, 'addNote'])->name('contacts.add-note');
    });

    Route::middleware('permission:contacts.delete')->group(function () {
        Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
        Route::post('contacts/bulk-action', [AdminContactController::class, 'bulkAction'])->name('contacts.bulk-action');
    });

    Route::middleware('permission:contacts.view')->group(function () {
        Route::get('contacts', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::get('contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
    });

    /*
    |--------------------------------------------------------------------------
    | RENDEZ-VOUS (Appointments)
    |--------------------------------------------------------------------------
    */

    // Route::resource('appointments', AdminAppointmentController::class);
    // Route::post('appointments/{appointment}/update-status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.update-status');

    /*
    |--------------------------------------------------------------------------
    | RECETTES (Recipes)
    |--------------------------------------------------------------------------
    */

    Route::resource('recipes', AdminRecipeController::class);

    /*
    |--------------------------------------------------------------------------
    | RÉALISATIONS (Realisations)
    |--------------------------------------------------------------------------
    */

    Route::resource('realisations', AdminRealisationController::class);

    /*
    |--------------------------------------------------------------------------
    | TÉMOIGNAGES
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:testimonials.approve')->group(function () {
        Route::post('testimonials/{testimonial}/approve', [AdminTestimonialController::class, 'approve'])->name('testimonials.approve');
        Route::post('testimonials/{testimonial}/reject', [AdminTestimonialController::class, 'reject'])->name('testimonials.reject');
    });

    Route::middleware('permission:testimonials.delete')->group(function () {
        Route::delete('testimonials/{testimonial}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');
    });

    Route::middleware('permission:testimonials.view')->group(function () {
        Route::get('testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
        Route::get('testimonials/{testimonial}', [AdminTestimonialController::class, 'show'])->name('testimonials.show');
    });

    /*
    |--------------------------------------------------------------------------
    | SYSTÈME DE DISPONIBILITÉS ET RENDEZ-VOUS
    |--------------------------------------------------------------------------
    */

    // DISPONIBILITÉS (Availability Periods) - CREATE en premier
    Route::middleware('permission:availabilities.create')->group(function () {
        Route::get('availabilities/create', [AdminAvailabilityPeriodController::class, 'create'])->name('availabilities.create');
        Route::post('availabilities', [AdminAvailabilityPeriodController::class, 'store'])->name('availabilities.store');
    });

    Route::middleware('permission:availabilities.edit')->group(function () {
        Route::get('availabilities/{availability}/edit', [AdminAvailabilityPeriodController::class, 'edit'])->name('availabilities.edit');
        Route::put('availabilities/{availability}', [AdminAvailabilityPeriodController::class, 'update'])->name('availabilities.update');
        Route::post('availabilities/{availability}/toggle', [AdminAvailabilityPeriodController::class, 'toggle'])->name('availabilities.toggle');
    });

    Route::middleware('permission:availabilities.delete')->group(function () {
        Route::delete('availabilities/{availability}', [AdminAvailabilityPeriodController::class, 'destroy'])->name('availabilities.destroy');
    });

    Route::middleware('permission:availabilities.view')->group(function () {
        Route::get('availabilities', [AdminAvailabilityPeriodController::class, 'index'])->name('availabilities.index');
        Route::get('availabilities/{availability}', [AdminAvailabilityPeriodController::class, 'show'])->name('availabilities.show');
    });

    // ÉVÉNEMENTS (Events) - CREATE en premier
    Route::middleware('permission:events.create')->group(function () {
        Route::get('events/create', [AdminEventController::class, 'create'])->name('events.create');
        Route::post('events', [AdminEventController::class, 'store'])->name('events.store');
    });

    Route::middleware('permission:events.edit')->group(function () {
        Route::get('events/{event}/edit', [AdminEventController::class, 'edit'])->name('events.edit');
        Route::put('events/{event}', [AdminEventController::class, 'update'])->name('events.update');
        Route::post('events/{event}/cancel', [AdminEventController::class, 'cancel'])->name('events.cancel');
        Route::post('events/{event}/complete', [AdminEventController::class, 'complete'])->name('events.complete');
    });

    Route::middleware('permission:events.delete')->group(function () {
        Route::delete('events/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');
    });

    Route::middleware('permission:events.view')->group(function () {
        Route::get('events', [AdminEventController::class, 'index'])->name('events.index');
        Route::get('events/calendar', [AdminEventController::class, 'calendar'])->name('events.calendar');
        Route::get('events/{event}', [AdminEventController::class, 'show'])->name('events.show');
    });

    // RENDEZ-VOUS (Appointments) - CREATE en premier
    Route::middleware('permission:appointments.create')->group(function () {
        Route::get('appointments/create', [AdminAppointmentController::class, 'create'])->name('appointments.create');
        Route::post('appointments', [AdminAppointmentController::class, 'store'])->name('appointments.store');
        Route::get('appointments/available-slots', [AdminAppointmentController::class, 'getAvailableSlots'])->name('appointments.available-slots');
    });

    Route::middleware('permission:appointments.edit')->group(function () {
        Route::get('appointments/{appointment}/edit', [AdminAppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('appointments/{appointment}', [AdminAppointmentController::class, 'update'])->name('appointments.update');
        Route::post('appointments/{appointment}/update-status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.update-status');
        Route::post('appointments/{appointment}/confirm', [AdminAppointmentController::class, 'confirm'])->name('appointments.confirm');
        Route::post('appointments/{appointment}/cancel', [AdminAppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::post('appointments/{appointment}/complete', [AdminAppointmentController::class, 'complete'])->name('appointments.complete');
    });

    Route::middleware('permission:appointments.delete')->group(function () {
        Route::delete('appointments/{appointment}', [AdminAppointmentController::class, 'destroy'])->name('appointments.destroy');
    });

    Route::middleware('permission:appointments.view')->group(function () {
        Route::get('appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
    });

    /*
    |--------------------------------------------------------------------------
    | BIBLIOGRAPHIE
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:bibliography.edit')->group(function () {
        Route::get('bibliography/edit', [AdminBibliographyController::class, 'edit'])->name('bibliography.edit');
        Route::put('bibliography', [AdminBibliographyController::class, 'update'])->name('bibliography.update');
    });

    Route::middleware('permission:bibliography.view')->group(function () {
        Route::get('bibliography', [AdminBibliographyController::class, 'index'])->name('bibliography.index');
    });

    /*
    |--------------------------------------------------------------------------
    | PUBLICITÉS DE SERVICES
    |--------------------------------------------------------------------------
    */

    // CREATE en premier (avant show)
    Route::middleware('permission:pub-services.create')->group(function () {
        Route::get('pub-services/create', [AdminPubServiceController::class, 'create'])->name('pub-services.create');
        Route::post('pub-services', [AdminPubServiceController::class, 'store'])->name('pub-services.store');
    });

    Route::middleware('permission:pub-services.edit')->group(function () {
        Route::get('pub-services/{pubService}/edit', [AdminPubServiceController::class, 'edit'])->name('pub-services.edit');
        Route::put('pub-services/{pubService}', [AdminPubServiceController::class, 'update'])->name('pub-services.update');
    });

    Route::middleware('permission:pub-services.publish')->group(function () {
        Route::post('pub-services/{pubService}/toggle-publish', [AdminPubServiceController::class, 'togglePublish'])->name('pub-services.toggle-publish');
    });

    Route::middleware('permission:pub-services.approve')->group(function () {
        Route::post('pub-services/{pubService}/approve', [AdminPubServiceController::class, 'approve'])->name('pub-services.approve');
        Route::post('pub-services/{pubService}/reject', [AdminPubServiceController::class, 'reject'])->name('pub-services.reject');
    });

    Route::middleware('permission:pub-services.delete')->group(function () {
        Route::delete('pub-services/{pubService}', [AdminPubServiceController::class, 'destroy'])->name('pub-services.destroy');
    });

    // VIEW en dernier (après toutes les autres routes)
    Route::middleware('permission:pub-services.view')->group(function () {
        Route::get('pub-services', [AdminPubServiceController::class, 'index'])->name('pub-services.index');
        Route::get('pub-services/{pubService}', [AdminPubServiceController::class, 'show'])->name('pub-services.show');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMINISTRATION
    |--------------------------------------------------------------------------
    */

    // Utilisateurs - CREATE en premier
    Route::middleware('permission:users.create')->group(function () {
        Route::get('users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
    });

    Route::middleware('permission:users.edit')->group(function () {
        Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    });

    Route::middleware('permission:users.delete')->group(function () {
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware('permission:users.view')->group(function () {
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    });

    // Rôles & Permissions - CREATE en premier
    Route::middleware('permission:roles.create')->group(function () {
        Route::get('roles/create', [AdminRoleController::class, 'create'])->name('roles.create');
        Route::post('roles', [AdminRoleController::class, 'store'])->name('roles.store');
    });

    Route::middleware('permission:roles.edit')->group(function () {
        Route::get('roles/{role}/edit', [AdminRoleController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{role}', [AdminRoleController::class, 'update'])->name('roles.update');
        Route::post('roles/{role}/permissions', [AdminRoleController::class, 'updatePermissions'])->name('roles.permissions');
    });

    Route::middleware('permission:roles.delete')->group(function () {
        Route::delete('roles/{role}', [AdminRoleController::class, 'destroy'])->name('roles.destroy');
    });

    Route::middleware('permission:roles.view')->group(function () {
        Route::get('roles', [AdminRoleController::class, 'index'])->name('roles.index');
        Route::get('roles/{role}', [AdminRoleController::class, 'show'])->name('roles.show');
    });

    // Paramètres
    Route::middleware('permission:settings.edit')->group(function () {
        Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });

    Route::middleware('permission:settings.view')->group(function () {
        Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    });

    // Routes admin
    // Route::middleware(['auth', 'permission:media.manage'])->prefix('admin')->name('admin.')->group(function () {
    //     Route::get('media', [AdminMediaController::class, 'index'])->name('media.index');
    //     Route::get('media/create', [AdminMediaController::class, 'create'])->name('media.create');

    //     // Images
    //     Route::post('media/images', [AdminMediaController::class, 'storeImages'])->name('media.storeImages');
    //     Route::post('media/images/{id}/toggle', [AdminMediaController::class, 'toggleImage'])->name('media.toggleImage');
    //     Route::delete('media/images/{id}', [AdminMediaController::class, 'destroyImage'])->name('media.destroyImage');
    //     Route::post('media/images/bulk-delete', [AdminMediaController::class, 'bulkDeleteImages'])->name('media.bulkDeleteImages');

    //     // Vidéos
    //     Route::post('media/videos', [AdminMediaController::class, 'storeVideo'])->name('media.storeVideo');
    //     Route::post('media/videos/{id}/toggle', [AdminMediaController::class, 'toggleVideo'])->name('media.toggleVideo');
    //     Route::delete('media/videos/{id}', [AdminMediaController::class, 'destroyVideo'])->name('media.destroyVideo');
    //     Route::post('media/videos/bulk-delete', [AdminMediaController::class, 'bulkDeleteVideos'])->name('media.bulkDeleteVideos');
    // });

    // Index avec filtre
    Route::middleware('permission:media_images.view')->group(function () {
        Route::get('media', [AdminMediaController::class, 'index'])->name('media.index');
    });

    // Images
    Route::middleware('permission:media_images.create')->group(function () {
        Route::post('media/images', [AdminMediaController::class, 'storeImages'])->name('media.storeImages');
    });

    Route::middleware('permission:media_images.edit')->group(function () {
        Route::post('media/images/{id}/toggle', [AdminMediaController::class, 'toggleImage'])->name('media.toggleImage');
    });

    Route::middleware('permission:media_images.delete')->group(function () {
        Route::delete('media/images/{id}', [AdminMediaController::class, 'destroyImage'])->name('media.destroyImage');
        Route::post('media/images/bulk-delete', [AdminMediaController::class, 'bulkDeleteImages'])->name('media.bulkDeleteImages');
    });

    // Vidéos
    Route::middleware('permission:media_videos.create')->group(function () {
        Route::post('media/videos', [AdminMediaController::class, 'storeVideo'])->name('media.storeVideo');
    });

    Route::middleware('permission:media_videos.edit')->group(function () {
        Route::post('media/videos/{id}/toggle', [AdminMediaController::class, 'toggleVideo'])->name('media.toggleVideo');
    });

    Route::middleware('permission:media_videos.delete')->group(function () {
        Route::delete('media/videos/{id}', [AdminMediaController::class, 'destroyVideo'])->name('media.destroyVideo');
        Route::post('media/videos/bulk-delete', [AdminMediaController::class, 'bulkDeleteVideos'])->name('media.bulkDeleteVideos');
    });

    // Réseaux Sociaux
    // Route::middleware('permission:settings.edit')->group(function () {
    //     Route::get('social-links', [SocialLinkController::class, 'index'])->name('social-links.index');
    //     Route::get('social-links/create', [SocialLinkController::class, 'create'])->name('social-links.create');
    //     Route::post('social-links', [SocialLinkController::class, 'store'])->name('social-links.store');
    //     Route::get('social-links/{socialLink}/edit', [SocialLinkController::class, 'edit'])->name('social-links.edit');
    //     Route::put('social-links/{socialLink}', [SocialLinkController::class, 'update'])->name('social-links.update');
    //     Route::delete('social-links/{socialLink}', [SocialLinkController::class, 'destroy'])->name('social-links.destroy');
    //     Route::post('social-links/{socialLink}/toggle', [SocialLinkController::class, 'toggle'])->name('social-links.toggle');
    //     Route::post('social-links/update-order', [SocialLinkController::class, 'updateOrder'])->name('social-links.update-order');
    // });

    // // Paramètres
    // Route::middleware('permission:settings.view')->group(function () {
    //     Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    // });

    // Route::middleware('permission:settings.edit')->group(function () {
    //     Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    // });

    // Journaux d'activité
    Route::middleware('permission:logs.view')->group(function () {
        Route::delete('activity-logs/clear', [AdminActivityLogController::class, 'clear'])->name('activity-logs.clear');
        Route::get('activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('activity-logs/{activityLog}', [AdminActivityLogController::class, 'show'])->name('activity-logs.show');
    });
});

require __DIR__.'/auth.php';
