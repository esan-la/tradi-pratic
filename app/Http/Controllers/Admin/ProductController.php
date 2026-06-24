<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\MediaStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $mediaService;

    public function __construct(MediaStorageService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre par statut de publication
        if ($request->has('is_published')) {
            $query->where('is_published', $request->is_published);
        }

        // Filtre par stock
        if ($request->has('stock_filter')) {
            if ($request->stock_filter === 'out_of_stock') {
                $query->where('stock', 0);
            } elseif ($request->stock_filter === 'low_stock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 10);
            }
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:10240',
            'gallery.*' => 'nullable|image|max:10240',
            'is_published' => 'boolean',
        ]);

        // Générer le slug si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Upload image principale
        if ($request->hasFile('image')) {
            $validated['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'products'
            );
        }

        // Upload galerie
        if ($request->hasFile('gallery')) {
            $validated['gallery'] = json_encode(
                $this->mediaService->uploadMultipleImages(
                    $request->file('gallery'),
                    'products/gallery'
                )
            );
        }

        $product = Product::create($validated);

        activity()
            ->performedOn($product)
            ->causedBy(auth()->user())
            ->log('Création d\'un produit : ' . $product->name);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['orderItems' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:10240',
            'gallery.*' => 'nullable|image|max:10240',
            'is_published' => 'boolean',
        ]);

        // Upload nouvelle image
        if ($request->hasFile('image')) {
            if ($product->image) {
                $this->mediaService->delete($product->image);
            }
            $validated['image'] = $this->mediaService->uploadImage(
                $request->file('image'),
                'products'
            );
        }

        // Upload nouvelle galerie
        if ($request->hasFile('gallery')) {
            if ($product->gallery) {
                $oldGallery = is_array($product->gallery) ? $product->gallery : json_decode($product->gallery, true);
                if ($oldGallery) {
                    $this->mediaService->deleteMultiple($oldGallery);
                }
            }
            $validated['gallery'] = json_encode(
                $this->mediaService->uploadMultipleImages(
                    $request->file('gallery'),
                    'products/gallery'
                )
            );
        }

        $product->update($validated);

        activity()
            ->performedOn($product)
            ->causedBy(auth()->user())
            ->log('Modification d\'un produit : ' . $product->name);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Supprimer les images
        if ($product->image) {
            $this->mediaService->delete($product->image);
        }

        if ($product->gallery) {
            $gallery = is_array($product->gallery) ? $product->gallery : json_decode($product->gallery, true);
            if ($gallery) {
                $this->mediaService->deleteMultiple($gallery);
            }
        }

        $name = $product->name;
        $product->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Suppression d\'un produit : ' . $name);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé avec succès.');
    }

    /**
     * Toggle publication status
     */
    public function togglePublish(Product $product)
    {
        $product->update(['is_published' => !$product->is_published]);

        return back()->with('success', 'Statut de publication mis à jour.');
    }
}
