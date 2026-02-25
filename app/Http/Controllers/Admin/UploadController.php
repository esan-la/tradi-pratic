<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * Upload d'image pour TinyMCE
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
        ]);

        try {
            $file = $request->file('file');
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('editor/images', $filename, 'public');

            return response()->json([
                'location' => asset('storage/' . $path),
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de l\'upload: ' . $e->getMessage()
            ], 500);
        }
    }
}

// ============================================
// ROUTE À AJOUTER DANS routes/web.php
// ============================================

/*
Route::middleware(['auth', 'permission:realisations.create'])->group(function () {
    Route::post('admin/upload/image', [App\Http\Controllers\Admin\UploadController::class, 'uploadImage'])
        ->name('admin.upload.image');
});
*/
